<?php

namespace Tests\Feature;

use Tests\TestCase;

class TeamMembersDomXssTest extends TestCase
{
    public function test_team_member_employee_options_are_built_with_text_api(): void
    {
        $view = file_get_contents(resource_path('views/hr/teams/members.blade.php'));

        $this->assertIsString($view);
        $this->assertStringContainsString('new Option(label, String(employee.id', $view);
        $this->assertStringNotContainsString("employee.name + ' (' + employee.code", $view);
        $this->assertStringNotContainsString('${employeeOptions()}', $view);
    }
}
