<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<style type="text/css">
			@font-face {
			    font-family: 'AkkuratBold';
			    src: url('{{ public_path() }}/assets/fonts/AkkBd_Pro_1.ttf');
			}
			@font-face {
			    font-family: 'Akkurat';
			    src: url('{{ public_path() }}/assets/fonts/AkkRg_Pro_1.ttf');
			}
			body { margin: 0; padding: 75pt 0 20pt; font-family: 'Akkurat'; color: #444; }
			h1 { font-weight: normal; font-family: 'AkkuratBold'; }
			#header { position: fixed; background-color: #eee; top: -34pt; right: -34pt; left: -34pt; padding: 35pt 34pt 15pt; }
			#header h1 { font-size: 30pt; margin: 0; }
			#meta {  margin: 0 0 10pt; }
			#meta h1 { font-size: 20pt; margin: 0; }
			#meta h1 span { color: #aaa; }
			#meta address { font-style: normal; font-family: 'Akkurat'; line-height: 1; }
			table { width: 100%; font-size: 10pt; margin-top: 10pt;}
			table th, table td { padding: 6px 0; text-align: left; vertical-align: top; }
			table th { border-bottom: 2pt solid #ddd; font-size: 10pt; font-weight: normal; }
			table .date { white-space: nowrap; width: 15%; }
			table .title { width: 55%; }
			table .amount { text-align: right; width: 15%; }
			table .hours { text-align: right; width: 15%; }
			table tbody td { border-bottom: 1pt solid #eee; }
			table tfoot td { font-size: 11pt; font-family: 'AkkuratBold'; }
			#footer { 
				color: #eee; position: fixed; background-color: #555;
				bottom: -34pt; left: -34pt; right: -34pt; 
				padding: 12pt 34pt 0 34pt; height: 44pt; 
				font-size: 9.5pt; line-height: 1.2;
			}
			#footer a { color: inherit; text-decoration: none; }
			#footer div { position: absolute; width: 240pt; }
			#footer div:first-child { left: 34pt; }
			#footer div:last-child { right: 34pt; text-align: right; }
			#footer div span { color: #aaa; width: 40pt; }
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
			<h1>{{ $project->client->name }} > <span>{{ $project->name }}</span></h1>
			@if ($project->client->address)
				<address>{!! nl2br($project->client->address) !!}</address>
			@endif
		</div>
		<table cellspacing="0">
			<thead>
				<tr>
					<th class="date">Date</th>
					<th>Task</th>
					@if (!$project->fixed)
					<th class="hours">Hours</th>
					<th class="amount">Amount</th>
					@endif
				</tr>
			</thead>
			<tbody>
			@foreach ($project->tasks as $task)
			<tr>
				<td class="date">{{ $task->closed_at->format('M j, Y') }}</td>
				<td>{{ $task->title }}</td>
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