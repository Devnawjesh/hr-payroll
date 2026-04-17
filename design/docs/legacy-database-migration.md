# Legacy Database Migration Notes

## Source

- Legacy app: `/Applications/XAMPP/xamppfiles/htdocs/madpos`
- SQL dump used as schema baseline: `/Applications/XAMPP/xamppfiles/htdocs/madpos/database/madpos.sql`
- CodeIgniter DB config: `/Applications/XAMPP/xamppfiles/htdocs/madpos/application/config/database.php`
- Configured database: `madpos-test` on `localhost` with driver `mysqli`
- Live database was not reachable during this audit because the local MariaDB socket was unavailable.

## Migration Scope

- Baseline tables found in dump: `24`
- This document captures the legacy schema before Laravel conversion and before any user-requested structural changes.
- Use this as the "before" reference when writing Laravel migrations and communicating schema changes to existing users.

## Key Legacy Observations

- Many monetary, quantity, and business date fields are stored as `varchar` instead of numeric/date types.
- Business identifiers such as `sale_id`, `pro_id`, `user_id`, `customer_id`, and `return_id` are strings alongside integer primary keys.
- There are no foreign key constraints in the dump, even where relationships are implied.
- Multiple tables use status enums such as `ACTIVE` / `INACTIVE` instead of booleans or lookup tables.
- Audit timestamps are inconsistent: some tables use `date`, some `datetime`, many use `varchar`, and several have no created/updated timestamps at all.
- Laravel migration work will likely need both a compatibility layer and a data-cleanup plan.

## Likely Relationships To Preserve Or Redesign

- `sub_category.cat_id` -> `category.cat_id`
- `product.cat_id` -> `category.cat_id`
- `product.subcat_id` -> `sub_category.subcat_id`
- `product.brand_id` -> `brand.brand_id`
- `product_image.pro_id` -> `product.pro_id`
- `product_color.pro_id` -> `product.pro_id`
- `product_color.color_id` -> `color.color_id`
- `product_size.pro_id` -> `product.pro_id`
- `product_size.size_id` -> `size.size_id`
- `customer_balance.customer_id` -> `customer.customer_id`
- `expense.cat_id` -> `expense_category.cat_id`
- `sales.customer_id` -> `customer.customer_id`
- `sales.entry_id` -> `users.user_id`
- `sales_details.sale_id` -> `sales.sale_id`
- `sales_details.product_id` -> `product.pro_id`
- `return_sales.customer_id` -> `customer.customer_id`
- `return_sales.sale_id` -> `sales.sale_id`
- `return_sales.entry_id` -> `users.user_id`
- `return_details.return_id` -> `return_sales.return_id`
- `return_details.product_id` -> `product.pro_id`
- `notes.user_id` -> `users.user_id`
- `social_link.user_id` -> `users.user_id`
- `to_do_list.user_id` -> `users.user_id`
- `user_group.user_id` -> `users.user_id`
- `user_group.group_id` -> `groups.id`

## High-Risk Type Conversions For Laravel

- Money-like values currently stored as strings: `product.pro_price`, `product.selling_price`, `sales.total_amount`, `sales.total_paid`, `sales.total_due`, `expense.amount`, `return_sales.return_total`, `return_details.total`
- Quantity-like values currently stored as strings: `product.quantity`, `product.sold_qty`, `sales_details.quantity`, `return_details.return_quantity`
- Legacy date strings that should become real dates or timestamps: `customer.entry_date`, `users.created_on`, `users.dob`, `customer.date_of_birth`, `notes.datetime`, `to_do_list.date`, `return_sales.return_date`
- Mixed identifier strategy to review: integer PK plus string business key in `users`, `customer`, `product`, `sales`, `return_sales`
- Inconsistent naming to normalize: `discount_end` vs `discount_starts`, `pro_summery` spelling, `to_dodata`, `useer_sales` naming in view layer

## Suggested Change Log Template

For each schema change you make in Laravel, record:

1. Legacy table and column
2. New Laravel table and column
3. Type change
4. Nullability/default change
5. Data backfill or transformation rule
6. Whether old code depends on the legacy name

## Table Inventory

### `brand`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `brand_id` | `int(14) NOT NULL` | `NO` | `-` |
| `brand_name` | `varchar(128)` | `YES` | `NULL` |
| `brand_status` | `enum('ACTIVE','INACTIVE') NOT NULL` | `NO` | `'INACTIVE'` |

Indexes:
- `ADD PRIMARY KEY (`brand_id`)`

Auto increment:
- `MODIFY `brand_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15`

### `category`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `cat_id` | `int(14) NOT NULL` | `NO` | `-` |
| `cat_name` | `varchar(64)` | `YES` | `NULL` |
| `cat_status` | `enum('ACTIVE','INACTIVE')` | `YES` | `'INACTIVE'` |

Indexes:
- `ADD PRIMARY KEY (`cat_id`)`

Auto increment:
- `MODIFY `cat_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22`

### `color`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `color_id` | `int(14) NOT NULL` | `NO` | `-` |
| `color_name` | `varchar(64)` | `YES` | `NULL` |
| `color_status` | `enum('ACTIVE','INACTIVE') NOT NULL` | `NO` | `'INACTIVE'` |

Indexes:
- `ADD PRIMARY KEY (`color_id`)`

Auto increment:
- `MODIFY `color_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23`

### `customer`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `customer_id` | `varchar(64)` | `YES` | `NULL` |
| `customer_name` | `varchar(256)` | `YES` | `NULL` |
| `email` | `varchar(256)` | `YES` | `NULL` |
| `phone_number` | `varchar(128)` | `YES` | `NULL` |
| `address` | `varchar(512)` | `YES` | `NULL` |
| `gender` | `enum('Male','Female') NOT NULL` | `NO` | `'Male'` |
| `status` | `enum('ACTIVE','INACTIVE') NOT NULL` | `NO` | `'ACTIVE'` |
| `image` | `varchar(256)` | `YES` | `NULL` |
| `date_of_birth` | `varchar(128)` | `YES` | `NULL` |
| `note` | `varchar(512)` | `YES` | `NULL` |
| `entry_date` | `varchar(256)` | `YES` | `NULL` |

Indexes:
- `ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`(128)) COMMENT 'unique email'`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24`

### `customer_balance`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `customer_id` | `varchar(128)` | `YES` | `NULL` |
| `total_invoice` | `varchar(128)` | `YES` | `NULL` |
| `total_paid` | `varchar(128)` | `YES` | `NULL` |
| `total_due` | `varchar(128)` | `YES` | `NULL` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23`

### `expense`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `cat_id` | `int(14) NOT NULL` | `NO` | `-` |
| `expense_name` | `varchar(256)` | `YES` | `NULL` |
| `amount` | `varchar(64)` | `YES` | `NULL` |
| `note` | `varchar(512)` | `YES` | `NULL` |
| `expense_by` | `varchar(128)` | `YES` | `NULL` |
| `expense_date` | `date NOT NULL` | `NO` | `-` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6`

### `expense_category`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `cat_id` | `int(14) NOT NULL` | `NO` | `-` |
| `category_name` | `varchar(128)` | `YES` | `NULL` |
| `status` | `enum('ACTIVE','INACTIVE') NOT NULL` | `NO` | `'INACTIVE'` |

Indexes:
- `ADD PRIMARY KEY (`cat_id`)`

Auto increment:
- `MODIFY `cat_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4`

### `groups`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `group_name` | `varchar(128)` | `YES` | `NULL` |
| `description` | `varchar(512)` | `YES` | `NULL` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT`

### `notes`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `user_id` | `varchar(128)` | `YES` | `NULL` |
| `comment_id` | `varchar(64)` | `YES` | `NULL` |
| `title` | `varchar(256)` | `YES` | `NULL` |
| `description` | `varchar(512)` | `YES` | `NULL` |
| `note_image` | `varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci` | `YES` | `NULL` |
| `datetime` | `varchar(128)` | `YES` | `NULL` |
| `notification_status` | `enum('seen','unseen') NOT NULL` | `NO` | `'unseen'` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60`

### `product`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `pro_id` | `varchar(128)` | `YES` | `NULL` |
| `cat_id` | `int(14)` | `YES` | `NULL` |
| `subcat_id` | `int(14)` | `YES` | `NULL` |
| `brand_id` | `int(14)` | `YES` | `NULL` |
| `pro_name` | `varchar(256)` | `YES` | `NULL` |
| `pro_sku` | `varchar(128)` | `YES` | `NULL` |
| `pro_price` | `varchar(64)` | `YES` | `NULL` |
| `selling_price` | `varchar(64)` | `YES` | `NULL` |
| `discount` | `varchar(64)` | `YES` | `NULL` |
| `discount_starts` | `varchar(64)` | `YES` | `NULL` |
| `discount_end` | `varchar(64)` | `YES` | `NULL` |
| `pro_summery` | `varchar(512)` | `YES` | `NULL` |
| `pro_details` | `varchar(1024)` | `YES` | `NULL` |
| `quantity` | `varchar(64)` | `YES` | `NULL` |
| `sold_qty` | `varchar(128)` | `YES` | `NULL` |
| `barcode` | `varchar(512)` | `YES` | `NULL` |
| `date` | `datetime NOT NULL` | `NO` | `CURRENT_TIMESTAMP` |

Indexes:
- `ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_id` (`pro_id`), ADD UNIQUE KEY `pro_sku` (`pro_sku`), ADD UNIQUE KEY `barcode` (`barcode`(212))`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30`

### `product_color`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `pro_id` | `varchar(64)` | `YES` | `NULL` |
| `color_id` | `int(14)` | `YES` | `NULL` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159`

### `product_image`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `pro_id` | `varchar(128)` | `YES` | `NULL` |
| `img_url` | `varchar(256)` | `YES` | `NULL` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95`

### `product_size`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `pro_id` | `varchar(64)` | `YES` | `NULL` |
| `size_id` | `int(14) NOT NULL` | `NO` | `-` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147`

### `return_details`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `return_id` | `varchar(64)` | `YES` | `NULL` |
| `product_id` | `varchar(64)` | `YES` | `NULL` |
| `return_quantity` | `varchar(64)` | `YES` | `NULL` |
| `deduction` | `varchar(64)` | `YES` | `NULL` |
| `total` | `varchar(64)` | `YES` | `NULL` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6`

### `return_sales`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `return_id` | `varchar(64)` | `YES` | `NULL` |
| `customer_id` | `varchar(64)` | `YES` | `NULL` |
| `sale_id` | `varchar(64)` | `YES` | `NULL` |
| `invoice_no` | `varchar(64)` | `YES` | `NULL` |
| `return_date` | `varchar(128)` | `YES` | `NULL` |
| `total_deduction` | `varchar(64)` | `YES` | `NULL` |
| `return_total` | `varchar(64)` | `YES` | `NULL` |
| `tax_percentage` | `varchar(64)` | `YES` | `NULL` |
| `tax_amount` | `varchar(64)` | `YES` | `NULL` |
| `entry_id` | `varchar(64)` | `YES` | `NULL` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12`

### `sales`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `sale_id` | `varchar(128)` | `YES` | `NULL` |
| `customer_id` | `varchar(128)` | `YES` | `NULL` |
| `invoice_no` | `varchar(128)` | `YES` | `NULL` |
| `total_amount` | `varchar(128)` | `YES` | `NULL` |
| `total_payable` | `varchar(64)` | `YES` | `NULL` |
| `tax_percentage` | `varchar(64)` | `YES` | `NULL` |
| `tax_amount` | `varchar(64)` | `YES` | `NULL` |
| `total_discount` | `varchar(128)` | `YES` | `NULL` |
| `total_paid` | `varchar(128)` | `YES` | `NULL` |
| `total_due` | `varchar(128)` | `YES` | `NULL` |
| `create_date` | `date NOT NULL` | `NO` | `-` |
| `entry_id` | `varchar(128)` | `YES` | `NULL` |

Indexes:
- `ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `invoice_no` (`invoice_no`), ADD UNIQUE KEY `sale_id` (`sale_id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9`

### `sales_details`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `sl_id` | `int(14) NOT NULL` | `NO` | `-` |
| `sale_id` | `varchar(128)` | `YES` | `NULL` |
| `product_id` | `varchar(128)` | `YES` | `NULL` |
| `quantity` | `varchar(128)` | `YES` | `NULL` |
| `mrp` | `varchar(64)` | `YES` | `NULL` |
| `total_amount` | `varchar(128)` | `YES` | `NULL` |

Indexes:
- `ADD PRIMARY KEY (`sl_id`)`

Auto increment:
- `MODIFY `sl_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16`

### `settings`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(11) NOT NULL` | `NO` | `-` |
| `sitelogo` | `varchar(128)` | `YES` | `NULL` |
| `sitetitle` | `varchar(256)` | `YES` | `NULL` |
| `description` | `varchar(512)` | `YES` | `NULL` |
| `copyright` | `varchar(128)` | `YES` | `NULL` |
| `contact` | `varchar(128)` | `YES` | `NULL` |
| `currency` | `varchar(128)` | `YES` | `NULL` |
| `symbol` | `varchar(64)` | `YES` | `NULL` |
| `system_email` | `varchar(128)` | `YES` | `NULL` |
| `address` | `varchar(256)` | `YES` | `NULL` |
| `timezone` | `varchar(64)` | `YES` | `NULL` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2`

### `size`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `size_id` | `int(14) NOT NULL` | `NO` | `-` |
| `size_name` | `varchar(64)` | `YES` | `NULL` |
| `size_status` | `enum('ACTIVE','INACTIVE') NOT NULL` | `NO` | `'INACTIVE'` |

Indexes:
- `ADD PRIMARY KEY (`size_id`)`

Auto increment:
- `MODIFY `size_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30`

### `social_link`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `user_id` | `varchar(128)` | `YES` | `NULL` |
| `facebook` | `varchar(256)` | `YES` | `NULL` |
| `twitter` | `varchar(256)` | `YES` | `NULL` |
| `google_plus` | `varchar(256)` | `YES` | `NULL` |
| `skype` | `varchar(256)` | `YES` | `NULL` |
| `flicker` | `varchar(256)` | `YES` | `NULL` |
| `youtube` | `varchar(256)` | `YES` | `NULL` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT`

### `sub_category`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `subcat_id` | `int(14) NOT NULL` | `NO` | `-` |
| `cat_id` | `int(14)` | `YES` | `NULL` |
| `subcat_name` | `varchar(64)` | `YES` | `NULL` |
| `subcat_status` | `enum('ACTIVE','INACTIVE') NOT NULL` | `NO` | `'INACTIVE'` |

Indexes:
- `ADD PRIMARY KEY (`subcat_id`)`

Auto increment:
- `MODIFY `subcat_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37`

### `to_do_list`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `user_id` | `varchar(64)` | `YES` | `NULL` |
| `to_dodata` | `varchar(256)` | `YES` | `NULL` |
| `date` | `varchar(128)` | `YES` | `NULL` |
| `value` | `varchar(14)` | `YES` | `NULL` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT`

### `user_group`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `user_id` | `varchar(128)` | `YES` | `NULL` |
| `group_id` | `int(14) NOT NULL` | `NO` | `-` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT`

### `users`

| Column | Type | Nullable | Default |
|---|---|---|---|
| `id` | `int(14) NOT NULL` | `NO` | `-` |
| `user_id` | `varchar(64)` | `YES` | `NULL` |
| `full_name` | `varchar(128)` | `YES` | `NULL` |
| `email` | `varchar(256)` | `YES` | `NULL` |
| `password` | `varchar(512)` | `YES` | `NULL` |
| `ip_address` | `varchar(512)` | `YES` | `NULL` |
| `forgotten_code` | `varchar(512)` | `YES` | `NULL` |
| `address` | `varchar(512)` | `YES` | `NULL` |
| `dob` | `varchar(128)` | `YES` | `NULL` |
| `image` | `varchar(128)` | `YES` | `NULL` |
| `contact` | `varchar(256)` | `YES` | `NULL` |
| `gender` | `enum('MALE','FEMALE') NOT NULL` | `NO` | `'MALE'` |
| `country` | `varchar(128)` | `YES` | `NULL` |
| `created_on` | `varchar(128)` | `YES` | `NULL` |
| `status` | `enum('ACTIVE','INACTIVE') NOT NULL` | `NO` | `'INACTIVE'` |
| `user_type` | `enum('User','Admin') NOT NULL` | `NO` | `'User'` |
| `confirm_code` | `varchar(128)` | `YES` | `NULL` |

Indexes:
- `ADD PRIMARY KEY (`id`)`

Auto increment:
- `MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40`
