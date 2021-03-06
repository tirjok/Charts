<?php

$graph = '
<svg ';
if ($model->responsive) {
    $graph .= "width='100%' height='100%'";
} else {
    $graph .= $model->height ? "height='$model->height' " : '';
    $graph .= $model->width ? "width='$model->width' " : '';
}
$graph .= " id='$model->id'></svg>
	<script>
		$(function() {
			";
            $i = 0;
            foreach ($model->datasets as $ds) {
                $graph .= "
                    var s$i = [
                        ";
                for ($k = 0; $k < count($ds['values']); $k++) {
                    $graph .= '{x: "'.$model->labels[$k].'", y: '.$ds['values'][$k].' },';
                }
                $graph .= '
                    ];
                ';
                $i++;
            }
            $graph .= "

			var xScale = new Plottable.Scales.Category();
			var yScale = new Plottable.Scales.Linear();

			var xAxis = new Plottable.Axes.Category(xScale, 'bottom');
  			var yAxis = new Plottable.Axes.Numeric(yScale, 'left');

			var plot = new Plottable.Plots.Area()
			  ";
              for ($i = 0; $i < count($model->datasets); $i++) {
                  $graph .= ".addDataset(new Plottable.Dataset(s$i))";
              }
              $graph .= '
			  .x(function(d) { return d.x; }, xScale)
			  .y(function(d) { return d.y; }, yScale)
			  '; $graph .= $model->colors ? ".attr('stroke', \"".$model->colors[0]."\").attr('fill', \"".$model->colors[0].'")' : ''; $graph .= "
			  .animated(true);

			  var title = new Plottable.Components.TitleLabel(\"$model->title\")
  			  .yAlignment('center');;

			  var label = new Plottable.Components.AxisLabel(\"$model->element_label\")
			  .yAlignment('center');

			 var table = new Plottable.Components.Table([[label, title],[yAxis, plot],[null, xAxis]]);
		 	table.renderTo('svg#$model->id');


			window.addEventListener('resize', function() {
			  table.redraw();
			});
		});
	</script>
";

return $graph;
