<html>
<head>
<title>Output Components</title>
</head>
<body xmlns:df="dangerframe">
<h1>Output Components</h1>
<h2>Label</h2>
<div df:id="apples" style="background-color: lightblue;">Apple test failed!</div>
<h2>MultiLineLabel</h2>
<div df:id="oranges">Orange test failed!</div>
<h2>Label inside WebMarkupContainer</h2>
<div df:id="pears"><div df:id="pineapples">Pears and Pinapples test failed!</div></div>
<h2>RepeatingView</h2>
<ul><li df:id="bananas" style="background-color: lightyellow;"></li></ul>
<h2>Loop</h2>
<ul><li df:id="grapes" style="background-color: lightgreen;"><p df:id="sub"></p></li></ul>
<h2>ListView</h2>
<table>
	<tr>
		<th>Limes</th>
		<th>GrapeFruits</th>
		<th>Oranges</th>
	</tr>
	<tr df:id="lemons">
		<td df:id="limes">Lemmons and limes test failed</td>
		<td df:id="grapefruits">Lemmons and grapefruits test failed</td>
		<td df:id="oranges">Lemmons and grapefruits test failed</td>
	</tr>
</table>
</body></html>