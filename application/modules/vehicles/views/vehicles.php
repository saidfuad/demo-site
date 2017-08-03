<script type="text/javascript">
	$(function () {
		jQuery().ready(function () {
                
                //jQuery(".date").datepicker({dateFormat: "dd.mm.yy", changeMonth: true, changeYear: true});
                jQuery("#list").jqGrid({
                    url: '<?= site_url('vehicles/get_grid_vehicles') ?>', //another controller function for generating data
                    mtype: "post", //Ajax request type. It also could be GET
                    datatype: "json", //supported formats XML, JSON or Arrray
                    colNames: ['No', 'VEHICLE ID','VEHICLE NAME', 'PLATE NUMBER','TYPE', 'CATEGORY','DRIVER', 'OWNER', 'STATUS'], //Grid column headings
                    colModel: [
                        {name: 'no', index: 'no', width: 20, align: "left",hidden:true},
                        {name: 'asset_id', index: 'asset_id', editable: false, edittype: 'text', width: 100, hidden: true, align: "left"},
                        {name: 'assets_friendly_nm', index: 'assets_friendly_nm', width: 100, align: "left", editable: true, edittype: 'text', frozen: true},
                        {name: 'assets_name', index: 'assets_name', editable: true,edittype: 'text', width: 100, align: "left",frozen: true},
						{name: 'assets_type_id', index: 'assets_type_id', edittype: 'select', editoptions: {dataUrl: 'vehicles/get_types_select'}, width: 100, align: "left"},
                        {name: 'assets_category_id', index: 'assets_category_id', editable: true, edittype: 'select', editoptions: {dataUrl: 'vehicles/get_categories_select'}, width: 100, align: "left"},
                        {name: 'personnel_id', index: 'personnel_id',editable: true, edittype: 'select', editoptions: {dataUrl: 'vehicles/get_drivers_select'}, width: 100, align: "left"},
                        {name: 'owner_id', index: 'owner_id', editable: true, edittype: 'select', editoptions: {dataUrl: 'vehicles/get_owners_select'}, width: 100, align: "left"},
                        {name: 'status', index: 'status', editable: true, edittype: 'select', editoptions: {value: {'active': 'Active', 'inactive': 'Inactive'}}, width: 100, align: "left"},
                        
                    ],

                    rowNum:20,
                    loadonce: true,
                    shrinkToFit: true,
                    autowidth: true,
                    width: 'auto',
                    height: 'auto',
                    rowList: [20, 50, 100, 200, 500],
                    pager: '#pager',
                    sortname: 'assets_friendly_nm',
                    viewrecords: true,
                    multiselect: true,
                    caption: "",
                    
                
                }).navGrid('#pager', {edit: true, add: false, del: false},
                	updateDialog('GET'),
                	updateDialog('GET'),
                    updateDialog('GET')
	            ).navButtonAdd('#pager', {
                    caption: "Export",
                    buttonicon: "ui-icon-export",
                    onClickButton: function () {
                       jqgrid_process_export('excel');
                    },
                    position: "last"
	            
	            }).navButtonAdd('#pager', {
	            	caption: "", buttonicon: "ui-icon-calculator",
                    title: "Choose Columns",
                    onClickButton: function () {
						var str;
						var colModel;
						var n;
                    
                        jQuery("#list").jqGrid('columnChooser', {
                        	width : 250, 
                        	height:150, 
                        	modal:true, 
                        	done:function(){ 
								c = jQuery('#colchooser_list select').val();
								var colModel = jQuery("#list").jqGrid("getGridParam", "colModel");
								str = ''; 
								var array_cols = [];
								
								jQuery(c).each(function (i) {
									n = c[i];
									console.log("x:"+n);
									if(colModel[n]['hidden']==false){
										array_cols.push(colModel[n]['name']);
									}
								});
									
								str = JSON.stringify(array_cols);	
								document.cookie = 'jqgrid_colchooser=' + str; 
							}, 

							"dialog_opts" : {"minWidth": 270} 
						});
					}
				});
			
			jQuery('#list').jqGrid('setFrozenColumns');
		
			function jqgrid_process_export(type) {
				var list = $("#list");
                var selectedRow = list.getGridParam("selrow");
                rowData = list.getRowData();
				var	str2 = JSON.stringify(rowData);	
				
				sessionStorage.setItem("row_data",str2);
				javascript:post_to_url('<?= site_url('excel_export/export') ?>', {'row_data': str2});
			}

			function post_to_url(url, params) {
				var form = document.createElement('form');
					form.action = url;
					form.method = 'POST';

				for (var i in params) {
					if (params.hasOwnProperty(i)) {
						var input = document.createElement('input');
						    input.type = 'hidden';
						    input.name = i;
            				input.value = params[i];
            				form.appendChild(input);
            		}
            	}
            	//alert(JSON.stringify(params));
            	form.submit();
			}	

			jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false, defaultSearch:'cn'}); 		
       		//Grouping Columns
            

            $('.jqgrow').mouseover(function(e) {
            	var rowId = $(this).attr('id');
                var dataFromTheRow = jQuery("#list").jqGrid('getRowData',rowId);// this is your jqgrid row object;
                alert('your jqgrid row object id = ' + dataFromTheRow.id);
            });
                
            function updateDialog(action) {
            	return {
                    url: '<?= base_url('index.php/farmers/operation/') ?>',
                    closeAfterAdd: true,
                    closeAfterEdit: true,
                    afterShowForm: function (formId) {}, 
                    modal: true, 
                    onclickSubmit: function (params) {
                        var list = $("#list");
                        var selectedRow = list.getGridParam("selrow");
                        rowData = list.getRowData(selectedRow);
                        params.url += '?fid=' + rowData.fid;
                        params.mtype = action;
                    
                }, 	afterComplete: function (response) {
                    	console.log('after del' + JSON.stringify(response));
                        
                        if (response.responseText === '1') {
                            alert("Operation Successful");
                        } else {
                            alert("Operation failed");
                        }
				}, width: "300"};
            }
            
            
            $(window).bind('resize', function() {
                var width = $('#jqGrid_container').width();
                $('#list').setGridWidth(width);
            });

        });
	});
</script>

<div id="container-fluid" id="page-wrapper">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="margin-10">
    			<table id="list" class="table table-striped table-hover"></table>
    			<div id="pager"></div>
            </div>
		</div>
    </div>
</div>    
    