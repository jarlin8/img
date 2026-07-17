/* global aawp_clicks_graph, ajaxurl, moment, Chart */

/**
 * AAWP Clicks Graph function.
 *
 * @since 3.20
 */

'use strict';

var AAWPClicksGraph = window.AAWPClicksGraph || ( function( document, window, $ ) {

	/**
	 * Elements reference.
	 *
	 * @since 3.20
	 *
	 * @type {object}
	 */
	var el = {

		$timespanSelect : $( '#aawp-clicks-graph-timespan' ),
		$groupSelect	: $( '#aawp-clicks-graph-group' ),
		$filterSelect	: $( '#aawp-clicks-graph-filter' ),
		$typeSelect		: $( '#aawp-clicks-graph-charttype' ),
		$canvas         : $( '#aawp-clicks-graph-chart' ),
	};

	/**
	 * Color scheme.
	 *
	 * @since 3.20
	 *
	 * @type {{pointBackgroundColor: string, backgroundColor: string, borderColor: string}}
	 */
	let aawpColors = {
		backgroundColor      : '#474369',
		hoverBackgroundColor : '#ff364f',
		borderColor          : '#FE6578',
		hoverBorderColor     : '#474369',
		pointBackgroundColor : 'rgba(255, 255, 255, 1)',
	};

	if ( aawp_clicks_graph.chart_type === 'line' ) {
		aawpColors.backgroundColor = '#474369';
	}

	/**
	 * Chart.js functions and properties.
	 *
	 * @since 3.20
	 *
	 * @type {object}
	 */
	var chart = {

		/**
		 * Chart.js instance.
		 *
		 * @since 3.20
		 */
		instance: null,

		/**
		 * Chart.js settings.
		 *
		 * @since 3.20
		 */
		settings: {
			type   : aawp_clicks_graph.chart_type,
			data   : {
				labels  : [],
				datasets: [ { ...{
					label: aawp_clicks_graph.i18n.clicks,
					data: [],
					borderWidth: 2,
					pointRadius: 4,
					pointBorderWidth: 1,
				}, ...aawpColors,
				} ],
			},
			options: {
				maintainAspectRatio        : false,
				scales                     : {
					xAxes: [ {
						type: 'category',
						distribution: 'series',
						ticks       : {
							display: true,
							align: 'inner',
							offset: true,
							crossAlign: 'center',
							beginAtZero: false,
							source     : 'labels',
							fontColor: '#50575e',
							padding    : 10,
							minRotation: 0,
							maxRotation: 0,
							callback   : function( value, index, values ) {

								// Distribute the ticks equally starting from a right side of xAxis.
								var gap = Math.floor( values.length / 7 );

								if ( gap < 1 ) {
									return value;
								}
								if ( ( values.length - index - 1 ) % gap === 0 ) {
									return value;
								}
							},
						},
					} ],
					yAxes: [ {
						ticks: {
							beginAtZero  : true,
							maxTicksLimit: 6,
							padding      : 20,
							callback     : function( value ) {

								// Make sure the tick value has no decimals.
								if ( Math.floor( value ) === value ) {
									return value;
								}
							},
						},
					} ],
				},
				elements                   : {
					line: {
						tension: 0,
					},
				},
				animation                  : {
					duration: 0,
				},
				hover                      : {
					animationDuration: 0,
				},
				legend                     : {
					display: false,
				},
				tooltips                   : {
					displayColors: false,
				},
				responsiveAnimationDuration: 0,
			},
		},

		/**
		 * Init Chart.js.
		 *
		 * @since 3.20
		 */
		init: function() {

			chart.optionsOnLoad();

			var ctx;

			if ( ! el.$canvas.length ) {
				return;
			}

			ctx = el.$canvas[ 0 ].getContext( '2d' );

			chart.instance = new Chart( ctx, chart.settings );

			chart.updateUI( aawp_clicks_graph.chart_data );
		},

		/**
		 * Options on load such as disable filterby dropdown when the groupby is tracking id.
		 */
		optionsOnLoad: function( ) {

			if ( 'custom' === $( '#aawp-clicks-graph-timespan').val() ) {
				$( '.aawp-clicks-date-range-filter').css( 'display', 'inline-block' );
				el.$timespanSelect.css( 'margin-top', '-5px' );
			}

			$( el.$groupSelect ).change( function() {

				if ( $( this ).val() === 'tracking_id' ) {
					el.$filterSelect.attr( 'disabled', true );
				} else {
					el.$filterSelect.attr( 'disabled', false );
				}
			});
		},

		/**
		 * Update Chart.js with a new AJAX data.
		 *
		 * @since 3.20
		 *
		 * @param {string} type The type of chart to display.
		 * @param {string} group Group the graph for (y-axis).
		 * @param {string} filter Filter the graph based on filter data.
		 * @param {string} timespan Timespan Filter the graph based on timespan.
		 * @param {boolean} overlay If the ajaxUpdate needs an overlay. Only needed to update the chart data, not type.
		 * 
		 */
		ajaxUpdate: function( type, group, filter, timespan, overlay ) {

			var data = {
				_wpnonce : aawp_clicks_graph.nonce,
				action   : 'aawp_clicks_graph_save_data',
				type     : type,
				group    : group,
				filter   : filter,
				timespan : timespan
			};

			if ( overlay ) {
				app.addOverlay( $( chart.instance.canvas ) );
			}

			$.post( ajaxurl, data, function( response ) {

				chart.updateUI( response );
			} );
		},

		/**
		 * Update Chart.js canvas.
		 *
		 * @since 3.20
		 *
		 * @param {object} data Dataset for the chart.
		 */
		updateUI: function( data ) {

			app.removeOverlay( el.$canvas );

			if ( $.isEmptyObject( data ) ) {
				chart.updateWithDummyData();
				chart.showEmptyDataMessage();
			} else {
				chart.updateData( data );
				chart.removeEmptyDataMessage();
			}

			chart.instance.data.labels = chart.settings.data.labels;
			chart.instance.data.datasets[ 0 ].data = chart.settings.data.datasets[ 0 ].data;

			chart.instance.update();
		},

		/**
		 * Update Chart.js settings data.
		 *
		 * @since 3.20
		 *
		 * @param {object} data Dataset for the chart.
		 */
		updateData: function( data ) {

			chart.settings.data.labels = [];
			chart.settings.data.datasets[ 0 ].data = [];

			chart.updateTotal( data );
		},

		/**
		 * Updates total clicks number in table title.
		 *
		 * @since 3.20
		 *
		 * @param {object} data Dataset for the chart.
		 */
		updateTotal: function( data ) {

			let totalCount = 0;

			$.each( data, function( index, value ) {

				totalCount = Number( totalCount ) + Number( value.count );

				var x_axis = value.day ?  value.day : value.x_axis;

				chart.settings.data.labels.push( x_axis );
				chart.settings.data.datasets[ 0 ].data.push( value.count );

			} );

			$( '#total-clicks-count' ).text( aawp_clicks_graph.i18n.total_clicks + ': ' + totalCount );
		},

		/**
		 * Update Chart.js settings with dummy data.
		 *
		 * @since 3.20
		 */
		updateWithDummyData: function() {

			chart.settings.data.labels = [];
			chart.settings.data.datasets[ 0 ].data = [];

			var end = new Date();
			var days = 30;
			var date;

			var minY = 5;
			var maxY = 20;
			var i;

			for ( i = 1; i <= days; i ++ ) {

				end.setDate(end.getDate() + 1);

				chart.settings.data.labels.push( date );
				chart.settings.data.datasets[ 0 ].data.push( {
					t: date,
					y: Math.floor( Math.random() * ( maxY - minY + 1 ) ) + minY,
				} );
			}
		},

		/**
		 * Display an error message if the chart data is empty.
		 *
		 * @since 3.20
		 */
		showEmptyDataMessage: function() {

			chart.removeEmptyDataMessage();
			el.$canvas.after( aawp_clicks_graph.empty_chart_html );
		},

		/**
		 * Remove all empty data error messages.
		 *
		 * @since 3.20
		 */
		removeEmptyDataMessage: function() {

			el.$canvas.siblings( '.aawp-error' ).remove();
		},

		/**
		 * Chart related event callbacks.
		 *
		 * @since 3.20
		 */
		events: {

			/**
			 * Update a chart on a group change.
			 *
			 * @since 3.20
			 */
			groupChanged: function() {

				var type = el.$typeSelect.val();
				var group = el.$groupSelect.val();
				var filter = el.$filterSelect.val();
				var timespan = el.$timespanSelect.val();

				chart.ajaxUpdate( type, group, filter, timespan, true );
			},

			/**
			 * Update a chart on a filter change.
			 *
			 * @since 3.20
			 */
			filterChanged: function() {

				var type = el.$typeSelect.val();
				var group = el.$groupSelect.val();
				var filter = el.$filterSelect.val();
				var timespan = el.$timespanSelect.val();

				chart.ajaxUpdate( type, group, filter, timespan, true );
			},

			/**
			 * Do things when the timespan dropdown change.
			 *
			 * @since 3.20
			 */
			timespanChanged: function() {

				var timespan = el.$timespanSelect.val();

				if ( 'custom' === timespan ) {
					$( '.aawp-clicks-date-range-filter' ).css( 'display', 'inline-block' );
					el.$timespanSelect.css( 'margin-top', '-5px' );
				} else {
					$( '.aawp-clicks-date-range-filter' ).hide();
				}
			},

			/**
			 * Update a chart on a type change.
			 *
			 * @since 3.20
			 */
			typeChanged: function() {

				var type = el.$typeSelect.val();
				var group = el.$groupSelect.val();
				var filter = el.$filterSelect.val();
				var timespan = el.$timespanSelect.val();

				chart.settings.type = type;

				chart.instance.update();
				chart.ajaxUpdate( type, group, filter, timespan, true );
			},
		},
	};

	/**
	 * Public functions and properties.
	 *
	 * @since 3.20
	 *
	 * @type {object}
	 */
	var app = {

		/**
		 * Publicly accessible Chart.js functions and properties.
		 *
		 * @since 3.20
		 */
		chart: chart,

		/**
		 * Start the engine.
		 *
		 * @since 3.20
		 */
		init: function() {
			$( app.ready );
		},

		/**
		 * Document ready.
		 *
		 * @since 3.20
		 */
		ready: function() {

			chart.init();
			app.events();
		},

		/**
		 * Register JS events.
		 *
		 * @since 3.20
		 */
		events: function() {

			app.chartEvents();
		},

		/**
		 * Register chart area JS events.
		 *
		 * @since 3.20
		 */
		chartEvents: function() {

			el.$groupSelect.change( function() {
				chart.events.groupChanged();
			} );

			el.$filterSelect.change( function() {
				chart.events.filterChanged();
			} );

			el.$typeSelect.change( function() {
				chart.events.typeChanged();
			} );

			el.$timespanSelect.change( function() {
				chart.events.timespanChanged();
			} );
		},

		/**
		 * Add an overlay to a graph block containing $el.
		 *
		 * @since 3.20
		 *
		 * @param {object} $el jQuery element inside a graph block.
		 */
		addOverlay: function( $el ) {

			if ( ! $el.parent().closest( '.aawp-clicks-graph-block-container' ).length ) {
				return;
			}

			app.removeOverlay( $el );
			$el.after( '<div class="aawp-clicks-graph-overlay"></div>' );
		},

		/**
		 * Remove an overlay from a graph block containing $el.
		 *
		 * @since 3.20
		 *
		 * @param {object} $el jQuery element inside a graph block.
		 */
		removeOverlay: function( $el ) {
			$el.siblings( '.aawp-clicks-graph-overlay' ).remove();
		},
	};

	// Provide access to public functions/properties.
	return app;

}( document, window, jQuery ) );

// Initialize.
AAWPClicksGraph.init();
