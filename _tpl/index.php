<h1>Tasks Assignment Diagram</h1>

<ul>
	<li>Height of row indicates number of tasks.</li>
	<li> Different color of cells indicates different projects.</li>
	<li> Click cells to go to the task.</li>
</ul>

<table border="1" style="border-collapse: collapse;">
	<thead>
		<tr>
			<th rowspan="2">User</th>
			<th colspan="<?= ($days+1) ?>"> <?php echo date('F Y') ?></th>
		</tr>
		<tr>
			<?php foreach (range(1, $days) as $day): ?>
				<th style="width:30px;"><?= $day ?></th>
			<?php endforeach ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $user): ?>
			<tr id="row-<?= $user['phid'] ?>">
				<td valign="top" style="padding:5px 10px">
					<img style="height:20px;" src="<?php echo $user['image'] ?>">
					<?= $user['realName'] ?>
				</td>
				<?php foreach (range(1, $days) as $day): ?>
					<td <?php if($day == date('j') ): ?> style="background:gray" <?php endif ?>  class="day-<?php echo mktime(0,0,0, date('n'), $day, date('Y')) ?>"></td>
				<?php endforeach ?>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
	
<script type="text/javascript" src="<?= $base_path ?>/assets/plugins/jquery/dist/jquery.js"></script>
<script type="text/javascript">

	var days = [];
	<?php foreach (range(1, $days) as $day): ?>
		days.push(<?= mktime(0,0,0, date('n'), $day, date('Y')) ?>);
	<?php endforeach ?>

	$(function(){
		$.ajax({
			url: "<?= $base_path ?>/api",
			type: "GET",
			success: function(response) {

				var users = response.users;
				for(var i in users) {

					for( projectPHID in users[i].targeted_tasks) {

						var tasks =  users[i].targeted_tasks[projectPHID];

						for(var x in tasks) {

							for(var n in days) {

								// remove hour/minuts/secends
								var xd = new Date(tasks[x].dateCreated*1000);
								var dd = new Date(xd.getFullYear(), xd.getMonth(), xd.getDate())

								var xtd = new Date(tasks[x]['auxiliary']['std:maniphest:creasindo:target']*1000);
								var dtd = new Date(xtd.getFullYear(), xtd.getMonth(), xtd.getDate())

								var url = 'javascript:;';
								var title = '';
								var bg = 'transparent';

								if(days[n] >= dd.getTime()/1000 && days[n] <= dtd.getTime()/1000 ) {
									title = tasks[x].objectName;
									url = tasks[x].uri;
									bg = response.projectColors[projectPHID];
								}

								$('#row-'+users[i].phid).find('.day-'+days[n])
									.append('<a href="'+url+'"  target="_blank" ><div title="'+title+'" style="font-size:0.7em;width:30px;height:10px;background-color:'+bg+'"></div></a>');
							}
						}
					}
				}
			}
		});
	});
</script>