<script type="text/javascript">
loadJQPLOT();
loadInfoBubble();

</script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('body').append('<div id="templates"></div>');
        $("#templates").hide();
        $("#templates").load("<?php echo base_url(); ?>index.php/home/templates", initDashboard);
		$('.ui-widget-header span').removeClass('hidden');

        function initDashboard() {
			var startId = 100;

          var dashboard<?php echo time(); ?> = $('#dashboard<?php echo time(); ?>').dashboard({
            layoutClass:'layout',
            json_data : {
              url: "<?php echo base_url(); ?>index.php/home/mywidgets/id/<?php echo $id; ?>"
            },
            // json feed; the widgets whcih you can add to your dashboard
            /*addWidgetSettings: {
              widgetDirectoryUrl:"jsonfeed/widgetcategories.json"
            },
			*/
            // Definition of the layout
            // When using the layoutClass, it is possible to change layout using only another class. In this case
            // you don't need the html property in the layout

            layouts :
              [
                { title: "<?php echo $this->lang->line("Layout1"); ?>",
                  id: "<?php echo $this->lang->line("layout1"); ?>",
                  image: "<?php echo base_url(); ?>assets/dash-lib/layouts/layout1.png",
                  html: '<div class="layout layout-a"><div class="column first column-first"></div></div>',
                  classname: 'layout-a'
                },
                { title: "<?php echo $this->lang->line("Layout2"); ?>",
                  id: "<?php echo $this->lang->line("layout2"); ?>",
                  image: "<?php echo base_url(); ?>assets/dash-lib/layouts/layout2.png",
                  html: '<div class="layout layout-aa"><div class="column first column-first"></div><div class="column second column-second"></div></div>',
                  classname: 'layout-aa'
                },
                { title: "<?php echo $this->lang->line("Layout3"); ?>",
                  id: "<?php echo $this->lang->line("layout3"); ?>",
                  image: "<?php echo base_url(); ?>assets/dash-lib/layouts/layout3.png",
                  html: '<div class="layout layout-ba"><div class="column first column-first"></div><div class="column second column-second"></div></div>',
                  classname: 'layout-ba'
                },
                { title: "<?php echo $this->lang->line("Layout4"); ?>",
                  id: "<?php echo $this->lang->line("layout4"); ?>",
                  image: "<?php echo base_url(); ?>assets/dash-lib/layouts/layout4.png",
                  html: '<div class="layout layout-ab"><div class="column first column-first"></div><div class="column second column-second"></div></div>',
                  classname: 'layout-ab'
                },

                { title: "<?php echo $this->lang->line("Layout5"); ?>",
                  id: "<?php echo $this->lang->line("layout5"); ?>",
                  image: "<?php echo base_url(); ?>assets/dash-lib/layouts/layout5.png",
                  html: '<div class="layout layout-aaa"><div class="column first column-first"></div><div class="column second column-second"></div><div class="column third column-third"></div></div>',
                  classname: 'layout-aaa'
                }
              ]

          }); // end dashboard call

          // binding for a widgets is added to the dashboard
          dashboard<?php echo time(); ?>.element.live('dashboardAddWidget',function(e, obj){
            var widget = obj.widget;
            dashboard<?php echo time(); ?>.addWidget({
              "id":startId++,
              "title":widget.title,
              "url":widget.url,
              "metadata":widget.metadata
              }, dashboard<?php echo time(); ?>.element.find('.column:first'));
          });

          // the init builds the dashboard. This makes it possible to first unbind events before the dashboars is built.
          dashboard<?php echo time(); ?>.init();
        }
		
      });
    </script>
  <!--div>
    <div class="headerlinks">
      <a class="editlayout headerlink" href="#"><?php echo $this->lang->line("Edit layout"); ?></a>
    </div>
  </div-->

  <div id="dashboard<?php echo time(); ?>" class="dashboard">
    <!-- this HTML covers all layouts. The 5 different layouts are handled by setting another layout classname -->
    <div class="layout">
      <div class="column first column-first"></div>
      <div class="column second column-second"></div>
      <div class="column third column-third"></div>
    </div>
  </div>
  <script type="text/javascript">
	<?php /* google analytic code. */ ?>
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-37380597-1']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>