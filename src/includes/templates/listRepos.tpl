<table>
<thead>
	<tr>
		<th>repo</th>
		<th>actions</th>
	</tr>
</thead>
<tbody>
{foreach from = $repos item = repo}
	<tr>
		<td><a target = "_new" href = "{$CFG_REPO_BASE}/{$repo->getName()}">{$repo->getName()}</a></td>
		<td>
			[
			<a href = "deleteRepository.php?name={$repo->getName()}">X</a> 
			]
		</td>
	</tr>
{/foreach}
</tbody>

</table>
