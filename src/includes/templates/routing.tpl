<h2>Routing</h2>

<table>	
	<tr>
		<th>type</th>
		<th>source</th>
		<th>operator</th>
		<th>destination</th>
		<th>dump</th>
	</tr>
{foreach from = $routes item = route}
	<tr>
		<td>{$route->type|default:'<em>null</em>'}</td>
		<td>{$route->source|default:'<em>null</em>'}</td>
		<td>{$route->operator}</td>
		<td>{$route->destination|default:'<em>null</em>'}</td>
		<td><pre>{$route|print_r}</pre></td>
	</tr>
{/foreach}
</table>
