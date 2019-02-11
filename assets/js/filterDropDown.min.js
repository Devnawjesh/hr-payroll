(function($){function parseInitArray(initArray)
{var filterDef={"columns":[],"columnsIdxList":[],"bootstrap":!1,"label":"Filter "};if(("bootstrap" in initArray)&&(typeof initArray.bootstrap==='boolean'))
{filterDef.bootstrap=initArray.bootstrap}
if(("label" in initArray)&&(typeof initArray.label==='string'))
{filterDef.label=initArray.label}
if("columns" in initArray)
{for(var i=0;i<initArray.columns.length;i++)
{var initColumn=initArray.columns[i];if(("idx" in initColumn)&&(typeof initColumn.idx==='number'))
{var idx=initColumn.idx;filterDef.columns[idx]={"title":null,"maxWidth":null};filterDef.columnsIdxList.push(idx);if(('title' in initColumn)&&(typeof initColumn.title==='string'))
{filterDef.columns[idx].title=initColumn.title}
if(('maxWidth' in initColumn)&&(typeof initColumn.maxWidth==='string'))
{filterDef.columns[idx].maxWidth=initColumn.maxWidth}}}}
return filterDef}
$(document).on('preInit.dt',function(e,settings)
{if(e.namespace!=='dt'){return}
var api=new $.fn.dataTable.Api(settings);var id=api.table().node().id;var initObj=api.init();if(!("filterDropDown" in initObj))return;var filterDef=parseInitArray(initObj.filterDropDown);if(filterDef.columns.length==0)return;var container=api.table().container();var filterWrapperId=id+"_filterWrapper";var divCssClass=filterWrapperId+" "+((filterDef.bootstrap)?"form-inline":"");$(container).prepend('<div id="'+filterWrapperId+'" class="'+divCssClass+'">'+filterDef.label+'</div>');api.columns(filterDef.columnsIdxList).every(function()
{var column=this;var idx=column.index();var colName=(filterDef.columns[idx].title!==null)?filterDef.columns[idx].title:$(this.header()).html();if(colName=="")colName='column '+(idx+1);var selectClass="form-control "+id+"_filterSelect";var selectId=id+"_filterSelect"+idx;$('#'+filterWrapperId).append('<select id="'+selectId+'" class="'+selectClass+'"></select>');var select=$("#"+selectId).empty().append('<option value="">('+colName+')</option>');if(screen.width>768)select.css('max-width',select.outerWidth());if(filterDef.columns[idx].maxWidth!==null)
{select.css('max-width',filterDef.columns[idx].maxWidth)}})});$(document).on('init.dt',function(e,settings)
{if(e.namespace!=='dt'){return}
var api=new $.fn.dataTable.Api(settings);var id=api.table().node().id;var initObj=api.init();if(!("filterDropDown" in initObj))return;var filterDef=parseInitArray(initObj.filterDropDown);var container=api.table().container();api.columns(filterDef.columnsIdxList).every(function()
{var column=this;var idx=column.index();var selectId=id+"_filterSelect"+idx;var select=$("#"+selectId);select.on('change',function()
{var val=$.fn.dataTable.util.escapeRegex($(this).val());column.search(val?'^'+val+'$':'',!0,!1).draw()});column.data().unique().sort().each(function(d,j)
{if(d!="")select.append('<option value="'+d+'">'+d+'</option>')})})})}(jQuery))