<table>
<thead>
	<tr>
		<th colspan = "2">repo</th>
		<th>updated</th>
		<th>package</th>
	</tr>
</thead>
<tbody>
{foreach from = $repos item = repo}
	<tr>
		<td><a target = "_new" href = "{$CFG_REPO_BASE}/{$repo.name}">{$repo.name}</a></td>
		<td>
			[
			<a href = "deleteRepository.php?name={$repo.name}">X</a> 
			]
		</td>
		<td><a target = "_new" href = "{$CFG_REPO_BASE}/{$repo.name}/{$repo.filename}">{$repo.uploaded}</a></td>
		<td>{$repo.filename}</td>
	</tr>
{/foreach}
</tbody>

</table>
