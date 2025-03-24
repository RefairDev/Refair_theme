<?php
/**
 * Template to display infographics on static page.
 *
 * @package refair
 */

?>
<section class="<?php echo esc_attr( $section_data['classes'] ); ?>">
	<?php $section = $section_data['section_meta']; ?>
	<div class="section-body">
		<div class="sub-section sub-section-description">
			<?php echo wp_kses_post( $section['description'] ); ?>
		</div>
		<div class="reuse-chart-wrapper">
			<canvas class="chart" id="main-chart">
				<div class="main-infographics"><img src="<?php echo esc_url( $section['main_infographic']['url'] ); ?>" alt="" class="main-infographics"></div>
			</canvas>
		</div>
		<div class="sub-section  sub-section-key-figures">
			<div class="key-figures">
				<?php
				foreach ( $section['key_figures'] as $key => $value ) {
					?>
					<div class="key-figure">
						<div class="figure init" data-max=<?php echo esc_attr( $value['figure'] ); ?> >0</div>
						<div class="description"><?php echo wp_kses_post( $value['description'] ); ?></div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>

	</div>
	<script>
		function animateValue(obj, start, end, duration) {
			let startTimestamp = null;
			const step = (timestamp) => {
				if (!startTimestamp) startTimestamp = timestamp;
				const progress = Math.min((timestamp - startTimestamp) / duration, 1);
				obj.innerHTML = Math.floor(progress * (end - start) + start);
				if (progress < 1) {
				window.requestAnimationFrame(step);
				}
			};
			window.requestAnimationFrame(step);
		}

		window.addEventListener('scroll', function(e) {
			const objs = document.querySelectorAll(".key-figures .figure.init");
			let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
			objs.forEach((figure) => {
				if (figure.offsetTop <= (scrollTop + window.innerHeight)){
					let max = Number.parseInt(figure.attributes['data-max'].value);
					animateValue(figure, 0,max, 2000);
					figure.classList.remove("init");
				}
			});
		});
		
		<?php
		$chart_data = $section['reuse_chart'];

		$chart_labels = array_map(
			function ( $elt ) {
				return $elt['data_title'];
			},
			$section['reuse_chart']['datas']
		);

		$chart_values = array_map(
			function ( $elt ) {
				return $elt['data_value'];
			},
			$section['reuse_chart']['datas']
		);

		$chart_descs = wp_json_encode(
			array_map(
				function ( $elt ) {
					return $elt['data_desc'];
				},
				$section['reuse_chart']['datas']
			)
		);

		$main_data      = $section['reuse_chart']['main_data'];
		$main_data_desc = $section['reuse_chart']['main_data_desc'];
		?>

		window.addEventListener('load', function(e) {
			const ctx = document.getElementById('main-chart').getContext('2d');
			const getOrCreateTooltip = (chart) => {
				let tooltipEl = chart.canvas.parentNode.querySelector('div');

				if (!tooltipEl) {
					tooltipEl = document.createElement('div');
					tooltipEl.style.background = 'rgba(0, 0, 0, 0.7)';
					tooltipEl.style.borderRadius = '3px';
					tooltipEl.style.color = 'white';
					tooltipEl.style.opacity = 1;
					tooltipEl.style.pointerEvents = 'none';
					tooltipEl.style.position = 'absolute';
					tooltipEl.style.transform = 'translate(-50%, 0)';
					tooltipEl.style.transition = 'all .1s ease';

					const table = document.createElement('table');
					table.style.margin = '0px';

					tooltipEl.appendChild(table);
					chart.canvas.parentNode.appendChild(tooltipEl);
				}

				return tooltipEl;
			};

			const externalTooltipHandler = (context) => {
				// // Tooltip Element
				// const {chart, tooltip} = context;
				// const tooltipEl = getOrCreateTooltip(chart);

				// // Hide if no tooltip
				// if (tooltip.opacity === 0) {
				// 	tooltipEl.style.opacity = 0;
				// 	return;
				// }

				// // Set Text
				// if (tooltip.body) {
				// 	const titleLines = tooltip.title || [];
				// 	const dataPoints = tooltip.dataPoints;

				// 	const tableHead = document.createElement('thead');

				// 	titleLines.forEach(title => {
				// 		const tr = document.createElement('tr');
				// 		tr.style.borderWidth = 0;

				// 		const th = document.createElement('th');
				// 		th.style.borderWidth = 0;
				// 		const text = document.createTextNode(title);

				// 		th.appendChild(text);
				// 		tr.appendChild(th);
				// 		tableHead.appendChild(tr);
				// 	});

				// 	const tableBody = document.createElement('tbody');
				// 	dataPoints.forEach((dataPoint, i) => {
				// 		const colors = tooltip.labelColors[i];

				// 		const span = document.createElement('span');
				// 		span.style.background = colors.backgroundColor;
				// 		span.style.borderColor = colors.borderColor;
				// 		span.style.borderWidth = '2px';
				// 		span.style.marginRight = '10px';
				// 		span.style.height = '10px';
				// 		span.style.width = '10px';
				// 		span.style.display = 'inline-block';

				// 		const tr = document.createElement('tr');
				// 		tr.style.backgroundColor = 'inherit';
				// 		tr.style.borderWidth = 0;

				// 		const td = document.createElement('td');
				// 		td.style.borderWidth = 0;

				// 		const text = document.createTextNode(`${dataPoint.label} ${dataPoint.formattedValue}%`);

				// 		//td.appendChild(span);
				// 		td.appendChild(text);
				// 		tr.appendChild(td);
				// 		tableBody.appendChild(tr);
				// 	});

				// 	const tableRoot = tooltipEl.querySelector('table');
				// 	tooltipEl.style.backgroundColor = tooltip.labelColors[0].backgroundColor;

				// 	// Remove old children
				// 	while (tableRoot.firstChild) {
				// 		tableRoot.firstChild.remove();
				// 	}

				// 	// Add new children
				// 	tableRoot.appendChild(tableHead);
				// 	tableRoot.appendChild(tableBody);
				// }

				// const {offsetLeft: positionX, offsetTop: positionY} = chart.canvas;

				// // Display, position, and set styles for font
				// tooltipEl.style.opacity = 1;
				// tooltipEl.style.left = positionX + tooltip.caretX + 'px';
				// tooltipEl.style.top = positionY + tooltip.caretY + 'px';
				// tooltipEl.style.font = tooltip.options.bodyFont.string;
				// tooltipEl.style.padding = tooltip.options.padding + 'px ' + tooltip.options.padding + 'px';
			};

			//Chartjs plugin to display data in doughnut center
			const centerKeyNumber = {
				id: 'center_key_number',
				beforeDraw: (chart, args, options) => {
					const pluginOptions = chart.config.options.plugins.centerKeyNumber;
					const ctx = chart.canvas.getContext('2d');
					const titleFont = `bold ${pluginOptions.titleFontSize}px Circular Std,sans-serif`;
					const textFont = `${pluginOptions.textFontSize}px Circular Std,sans-serif`;
					let paddingX = 0;
					
					let center = {"x":chart.chartArea.left + chart.chartArea.width /2, "y":chart.chartArea.top + chart.chartArea.height/2};
					if (pluginOptions.displayHeight){
						center.y = chart.chartArea.bottom + 10;
					}
					const marginTop=pluginOptions.marginTitleText;

					if(chart.currentLabelDescription == null){
						ctx.save();
						ctx.font = titleFont;
						ctx.fillStyle = pluginOptions.font;					
						//ctx.textBaseline = 'baseline';
						ctx.textBaseline = 'middle';
						ctx.textAlign = "center";
						let mainValueMetrics= ctx.measureText(pluginOptions.value);
						ctx.fillText(pluginOptions.value,center.x, center.y);
						ctx.font = textFont;
						let textLine = formatText(ctx,pluginOptions.label,mainValueMetrics.width);
						textLine.forEach((elt,idx)=>{
							ctx.font = textFont;
							let textMetrics = ctx.measureText(elt);									
							//ctx.textBaseline = 'baseline';
							ctx.textBaseline = 'middle';
							ctx.textAlign = "center";
							ctx.fillStyle = pluginOptions.fontColor;
							ctx.fillText(elt,center.x, center.y+marginTop+((textMetrics.actualBoundingBoxAscent+textMetrics.actualBoundingBoxDescent)*(idx+1)));
							ctx.restore();
						},ctx);
					}

				},
			}

			const labelDescription = {
				id: 'label_description',
				beforeInit: (chart, args, options) =>{

					
				},
				start: (chart, args, options) =>{
					if (chart.config.options.plugins.labelDescription.displayHeight){
						if (typeof chart.config.options.layout.padding ==="number"){
							chart.config.options.layout.padding = {bottom:chart.config.options.plugins.labelDescription.displayHeight};
						}else{
							chart.config.options.layout.padding.bottom=chart.config.options.layout.padding.bottom+chart.config.options.plugins.labelDescription.displayHeight;
						}
						chart.config.data.datasets[0].datalabels.offset= 30;
						let canvasStyle = getComputedStyle(chart.canvas.parentNode);
						let canvasHeight = canvasStyle.height;
						let intHeight=0;
						if (canvasHeight){ intHeight = parseInt(canvasHeight.replace("px",""))}
						chart.canvas.parentNode.style.height = (intHeight + chart.config.options.plugins.labelDescription.displayHeight).toString() + "px";
						
					}
				},
				beforeDraw: (chart, args, options) => {
					
					const pluginOptions = chart.config.options.plugins.labelDescription;
					const ctx = chart.canvas.getContext('2d');
					const center = {"x":  (chart.chartArea.left + chart.canvas.offsetWidth)/2, "y":(chart.canvas.offsetHeight/2)};

					ctx.save();
					if(chart.currentLabelDescription !=null ){
						// get contents
						let titleFontStyle = `bold ${pluginOptions.titleFontSize}px Circular Std,sans-serif`;
						let textFontStyle = `${pluginOptions.textFontSize}px Circular Std,sans-serif`;
						let labelText = chart.data.labels[chart.currentLabelDescription].toUpperCase();
						let valueText = chart.data.datasets[0].data[chart.currentLabelDescription]+"%";
						let textLines=[""];
						if (typeof pluginOptions.datas[chart.currentLabelDescription] === 'string'){
							textLines = pluginOptions.datas[chart.currentLabelDescription].split('\r\n');
						}

						// handle label content
						ctx.font = titleFontStyle;						
						ctx.textAlign = "left";
						ctx.textBaseline = 'middle';
						let labelTextMetrics  = ctx.measureText(labelText);
						labelTexts = formatText(ctx,labelText, chart.chartArea.height/2);
						let labelHeight = labelTexts.reduce((acc,elt)=>{let textMetrics = ctx.measureText(elt);return acc += textMetrics.fontBoundingBoxAscent+textMetrics.fontBoundingBoxDescent;},0);

						// handle texts content
						ctx.fillStyle = pluginOptions.font;
						ctx.font = textFontStyle;
						let maxTextsWidth = textLines.reduce((acc,elt)=>{return ctx.measureText(elt).width > acc ? ctx.measureText(elt).width: acc;},0);
						let textsHeight = textLines.reduce((acc,elt)=>{let textMetrics = ctx.measureText(elt);return acc += textMetrics.fontBoundingBoxAscent+textMetrics.fontBoundingBoxDescent;},0);

						let startY = center.y - (labelTextMetrics.fontBoundingBoxAscent + labelTextMetrics.fontBoundingBoxDescent);
						if (chart.config.options.plugins.labelDescription.displayHeight){
							startY = chart.chartArea.bottom + 10;
						}

						ctx.font = titleFontStyle;	
						var valueMetrics = ctx.measureText(valueText);
						let labelWidth = 0;
						let valueWidth = valueMetrics.width+20;

						labelTexts.forEach((elt,idx)=>{
							ctx.font = titleFontStyle;	
							let textMetrics = ctx.measureText(elt);	
							labelWidth < textMetrics.width ? labelWidth = textMetrics.width: labelWidth = labelWidth;
							ctx.fillText(elt,center.x-((textMetrics.width+valueWidth+10)/2), startY+((textMetrics.fontBoundingBoxAscent+textMetrics.fontBoundingBoxDescent)*(idx)));					
						},ctx);

						ctx.textAlign = "left";
						ctx.fillStyle = pluginOptions.fontColor;					
						ctx.font = titleFontStyle;	
						ctx.fillRect(center.x+labelWidth+10-((labelWidth+valueWidth+10)/2), startY-10-((valueMetrics.actualBoundingBoxAscent+valueMetrics.actualBoundingBoxDescent)/2), valueWidth, (valueMetrics.actualBoundingBoxAscent+valueMetrics.actualBoundingBoxDescent)+20);

						ctx.fillStyle = 'hsl(151, 38%, 23%)';
						ctx.textBaseline = 'middle';
						ctx.font = titleFontStyle;	
						ctx.fillText(valueText,center.x+labelWidth+20-((labelWidth+valueWidth+10)/2), startY);
				

						textLines.forEach((elt,idx)=>{
							ctx.font = textFontStyle;
							let textMetrics = ctx.measureText(elt);
							ctx.textAlign = "left";
							ctx.textBaseline = 'top';
							ctx.fillStyle = pluginOptions.fontColor;
							ctx.fillText(elt,center.x - (maxTextsWidth/2), startY+(labelTextMetrics.fontBoundingBoxAscent+labelTextMetrics.fontBoundingBoxDescent)/2+((textMetrics.fontBoundingBoxAscent+textMetrics.fontBoundingBoxDescent)*(idx+1)));
						},ctx);
					}
					ctx.restore();

				},
				beforeTooltipDraw: (chart, args, options) => {
					if (Array.isArray(chart.tooltip.dataPoints)&& chart.tooltip.opacity==1){
						chart.currentLabelDescription=chart.tooltip.dataPoints[0].dataIndex;
					}else{
						chart.currentLabelDescription=null;
					}
				},

			}

			// helerp function to split text according to width param
			const  formatText = (ctx, text, maxWidth)=>{
				let txtArr = text.split(" "); 
				return txtArr.reduce((acc,elt,idx)=>{
					
					if ((acc.length>0) && ctx.measureText(acc[acc.length-1]+" "+elt).width < maxWidth){
						acc[acc.length-1] = acc[acc.length-1] +" "+elt;
					}else{
						acc.push(elt);
					}
					return acc;
				},[]);
			}

			const merge = (...arguments) => {
				

				// Variables
				let target = {};

				// Merge the object into the target object
				let merger = (obj) => {
					for (let prop in obj) {
						if (obj.hasOwnProperty(prop)) {
							switch (Object.prototype.toString.call(obj[prop])) {
								case "[object Object]":
									{
										// If we're doing a deep merge and the property is an object
										target[prop] = merge(target[prop], obj[prop]);									
										break;
									}
								case "[object Array]":
									{
										// If we're doing a deep merge and the property is an object
										target[prop]=target[prop]||[];
										obj[prop].forEach((elt,idx)=>{
											target[prop][idx] = merge(target[prop][idx],elt);
										},target[prop])
										break;
									}
							
								default:
									{
										// Otherwise, do a regular merge
									target[prop] = obj[prop];
									break;
									}
							}
						}
					}
				};

				let merger_array = (arr) => {
					arr.forEach((elt,idx)=>{
						switch (Object.prototype.toString.call(elt)) {
							case "[object Object]":
								{
									// If we're doing a deep merge and the property is an object
									target[idx] = merge(target[idx], elt);									
									break;
								}
							case "[object Array]":
								{
									// If we're doing a deep merge and the property is an object
									target[idx]=target[idx]||[];
									elt.forEach((subElt,subIdx)=>{
										target[idx][subIdx] = merge(target[idx][subIdx], subElt);
									},target[idx])
									break;
								}
						
							default:
								{
									// Otherwise, do a regular merge
								target[idx] = elt;
								break;
								}
						}
					})
				}

				//Loop through each object and conduct a merge
				for (let i = 0; i < arguments.length; i++) {

					if (arguments[i]){
						switch (Object.prototype.toString.call(arguments[i])) {
							case "[object Object]":
								{
									merger(arguments[i]);							
									break;
								}
							case "[object Array]":
								{
									// If we're doing a deep merge and the property is an object
									merger_array(arguments[i]);
									break;
								}
						
							default:
								{
									// Otherwise, do a regular merge
									target = arguments[i];
									break;
								}
						}
					}
				}

				return target;
			};

			const commonCfg = {
				type: 'doughnut',
				data: {
					labels: ["<?php echo wp_kses_post( implode( '","', $chart_labels ) ); ?>"],
					datasets: [{
						label: 'dataset label',
						data: [<?php echo wp_kses_post( implode( ',', $chart_values ) ); ?>],
						backgroundColor: [
							'hsl(151, 38%, 23%)',
							'hsl(150, 35%, 29%)',
							'hsl(150, 38%, 39%)',
							'hsl(150, 28%, 49%)',
							'hsl(150, 18%, 59%)',
							'hsl(150, 08%, 69%)',
							'hsl(150, 00%, 79%)',
						], 
						datalabels: {
							anchor: 'end',
							align : 'end',
							offset: 30,
							backgroundColor: function(context) {
								return context.dataset.backgroundColor;
							},
							color: 'white',
							//color:'hsl(151, 38%, 23%)',
							padding: 6,
							font:{
								size: "24px"
							},
							formatter: function(value, context) {
								return `${context.chart.data.labels[context.dataIndex]} ${value}%`;
							}
						}
					}]
				},
				options: {
					maintainAspectRatio: false,
					rotation:260,
					interaction: {
						mode: 'index',
						intersect: true,
					},
					layout:{
						padding:{top: 100,left:0,bottom:100, right:0}
					},
					datasets:{
						doughnut:{
							hoverOffset: 15
						}
					},
					onHover(event,chartElement){
						if ( 0 === chartElement.length && event.chart.currentLabelDescription == 0){
							event.chart.currentLabelDescription=null;									
						}
					},
					plugins: {
						tooltip: {
							enabled: false,
							position: 'nearest',
							mode:"nearest",
							external: externalTooltipHandler
						},
						centerKeyNumber:{
							value:"<?php echo wp_kses_post( $main_data ); ?>",
							label:"<?php echo wp_kses_post( $main_data_desc ); ?>",
							// fontColor:'hsl(151, 38%, 23%)',
							fontColor: '#FFFFFF',
							titleFontSize:'72',
							textFontSize:'18',
							marginTitleText: 30
						},
						legend:{
							display: false
						},
						datalabels: {
							listeners: {
								enter: function(context) {
									context.chart.currentLabelDescription=context.dataIndex;
									return true;
								},
								leave: function(context) {
									// Receives `leave` events for any labels of any dataset.
									context.chart.currentLabelDescription=null;
									return true;
								}
							}
							
						},
						labelDescription:{
							datas: <?php echo wp_kses_post( $chart_descs ); ?>,
							// fontColor:'hsl(151, 38%, 23%)',
							fontColor: '#FFFFFF',
							titleFontSize:'30',
							textFontSize:'18',
						}


					}
				}
			};

			const canvasLeftOffset = 150;
			
			
			const xlCfg = {
				plugins: [ChartDataLabels, centerKeyNumber, labelDescription],
				options:{
					layout:{
						padding:{top: 100,left:canvasLeftOffset,bottom:100, right:0}
					},
				}
			};

			const mdCfg = {
				plugins: [ChartDataLabels, centerKeyNumber, labelDescription],
			};
			
			const xsCfg = {
				plugins: [centerKeyNumber, labelDescription],
				data: {
					datasets: [{
						datalabels: {
							offset: 0,
						}
					}]
				},
				options: {
					layout:{
						padding:0,
					},
					plugins: {
						centerKeyNumber:{
							titleFontSize:'60',
							textFontSize:'24',
							displayHeight: 150,
							marginTitleText: 20
						},
						labelDescription:{
							titleFontSize:'26',
							textFontSize:'18',
							displayHeight: 150
						}

					},
					datasets:{
						doughnut:{
							radius: "80%",
						}
					},
				}
			};
			
			var cfg={};

			if (ctx.canvas.parentNode.offsetWidth < 1140){			
				if(ctx.canvas.parentNode.offsetWidth >= 768){
					cfg = merge(commonCfg,mdCfg);	
				}else{
					cfg = merge(commonCfg,xsCfg);	
				}			
			}else{
				cfg = merge(commonCfg,xlCfg);
			}

			//Chart creation
			const myChart = new Chart(ctx,cfg );

		});
		</script>
</section>
