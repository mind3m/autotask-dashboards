<?php
	echo $this->Html->script( '/autotask/js/queueHealth' );

	echo '<li id="' . $iDashboardWidgetId . '" data-row="' . $iRow . '" data-col="' . $iCol . '" data-sizex="' . $iDataSizeX . '" data-sizey="' . $iDataSizeY . '">';
?>

		<div class="queue-health-counts" style="position: relative; margin-bottom: 20px;">
			<h4 class="jeditable" id="display_name"><?php echo $sTitle; ?></h4>
			<div id="container" style="height: 202px; margin: 0 auto"></div>
		</div>

	<?php
		echo '</li>';

		$iDaysToGoBack = Configure::read( 'Widget.QueueHealth.daysOfHistory' ) - 1;
		if( -1 == $iDaysToGoBack ) {
			$iDaysToGoBack = 6;
		}
	?>

<script>
	$(function () {

		Highcharts.setOptions(Highcharts.queueHealth);

		$('.queue-health-counts #container').highcharts({
			chart: {
				type: 'spline',
				 zoomType: 'x'
			},
			title: false,
			xAxis: {
				type: 'datetime',
				minTickInterval: 24 * 3600 * 1000,
				labels: {
					formatter: function() {
						return Highcharts.dateFormat('%e %b', this.value);
					}
				}
			},
			yAxis: {
				title: false,
				min: 0,
				minorGridLineWidth: 0,
				gridLineWidth: 0,
				alternateGridColor: null,
			},
			plotOptions: {
				spline: {
					lineWidth: 4,
					states: {
						hover: {
							lineWidth: 5
						}
					},
					marker: {
						enabled: false
					},
					pointInterval: 24 * 3600 * 1000, // one day
					pointStart: Date.UTC(<?php echo date( 'Y', strtotime( "-" . $iDaysToGoBack . " days" ) ); ?>, <?php echo ( date( 'm', strtotime( "-" . $iDaysToGoBack . " days" ) ) - 1 ); ?>, <?php echo date( 'd', strtotime( "-" . $iDaysToGoBack . " days" ) ); ?>)
				}
			},
			series: [
				<?php
					foreach ( $aData as $aQueueHealth ) {
						
						echo '{ name: \'' . $aQueueHealth['Queue']['name'] . '\', data: [';

							foreach ( $aQueueHealth['History'] as $iKey => $aQueueHealthHistory ) {

								if( 0 != $iKey ) {
									echo ',';
								}

								echo $aQueueHealthHistory['Queuehealthcount']['average_days_open'];

							}

						echo '] },';

					}
				?>
			]
			,
			navigation: {
				menuItemStyle: {
					fontSize: '10px'
				}
			},
			exporting: {
				enabled: false
			},
		});

	});

</script>

<?php if( 'Dashboards' == $this->name && 'reorganize' == $this->action ) { ?>

	<script type="text/javascript">

		$(function() {

			$( 'li#<?php echo $iDashboardWidgetId; ?> .jeditable' ).editable( '/autotask/dashboardwidgets/edit/<?php echo $iDashboardWidgetId; ?>', {
				indicator : 'Saving..',
				tooltip   : 'Click to edit',
				onblur : 'submit'
			});

		});

	</script>

<?php } ?>