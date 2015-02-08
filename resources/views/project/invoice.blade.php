<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<style type="text/css">
			body { margin: 0; padding: 50pt 0 20pt; font-family: sans-serif; color: #444; }
			#header { position: fixed; background-color: #eee; top: -34pt; right: -34pt; left: -34pt; padding: 20pt 34pt 0; }
			#header h1 { font-weight: normal; }
			#meta {  margin: 20pt 0 10pt; }
			#meta h1 { font-size: 20pt; margin: 0; }
			#meta h1 span { color: #aaa; font-weight: normal; }
			#meta h2 { color: #777; font-weight: normal; font-size: 14pt; margin: 0; }
			table { width: 100%; font-size: 10pt; }
			table th, table td { padding: 6px 0; text-align: left; vertical-align: top; }
			table th { border-bottom: 2pt solid #ccc; font-weight: normal; font-size: 10pt; color: #bbb; }
			table .title { width: 55%; }
			table .date { white-space: nowrap; text-align: right; width: 15%; }
			table .amount { text-align: right; width: 15%; }
			table .amount span { color: #999; margin-right: 15pt; }
			table .hours { text-align: right; width: 15%; }
			table tbody td { border-bottom: 1px solid #ccc; }
			table tfoot td { font-weight: bold; font-size: 14pt; }
			#footer { 
				color: #eee; position: fixed; background-color: #555;
				bottom: -34pt; left: -34pt; right: -34pt; 
				padding: 10pt 34pt 0 34pt; height: 40pt; 
				font-size: 9.5pt; line-height: 1.4;
			}
			#footer a { color: inherit; text-decoration: none; }
			#footer div { position: absolute; width: 240pt; }
			#footer div:first-child { left: 34pt; }
			#footer div:last-child { right: 34pt; text-align: right; }
			#footer div span { color: #aaa; }
		</style>
	</head>
	<body>
		<div id="header">
			<h1>Josh Reisner</h1>
		</div>
		<div id="footer">
			<div>
				<a href="mailto:josh@joshreisner.com">josh@joshreisner.com</a><br>
				3024 Aspen Drive, Santa Clara, CA 95051
			</div>
			<div>
				<span>TEL</span> <a href="tel:9172848483">917-284-8483</a><br>
				<span>SSN</span> 231-08-0018
			</div>
		</div>
		<div id="meta">
			<h1>{{ $project->client->name }} <span>{{ $project->name }}</span></h1>
			<h2>created {{ date('M j, Y') }}</h2>
		</div>
		<table cellspacing="0">
			<thead>
				<tr>
					<th>Task</th>
					<th class="date">Date</th>
					@if (!$project->fixed)
					<th class="hours">Hours</th>
					<th class="amount">Amount</th>
					@endif
				</tr>
			</thead>
			<tbody>
			@foreach ($project->tasks as $task)
			<tr>
				<td>{{ $task->title }}</td>
				<td class="date">{{ $task->closed_at->format('M j, Y') }}</td>
				@if (!$project->fixed)
				<td class="hours">{{ format_number($task->hours) }}</td>
				<td class="amount">@if ($task->amount > 0){{ $task->amount }}@else <span>~</span> @endif</td>
				@endif
			</tr>
			@endforeach
			</tbody>
			<tfoot>
				<tr>
					@if (!$project->fixed)
					<td colspan="2"></td>
					<td class="hours">{{ format_number($project->hours) }}</td>
					@else
					<td></td>
					@endif
					<td class="amount">{{ format_money($project->amount) }}</td>
				</tr>
			</tfoot>
		</table>
	</body>
</html>