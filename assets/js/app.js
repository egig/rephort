window.App = (function($) {

	return {

		init: function(baseUrl, days) {

			$(function(){
				$.ajax({
					url: baseUrl+"/api",
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
										var content = '';
										var bg = 'transparent';

										if(days[n] >= dd.getTime()/1000 && days[n] <= dtd.getTime()/1000 ) {
											title = tasks[x].objectName;
											url = tasks[x].uri;
											content = tasks[x].title;
											bg = response.projectColors[projectPHID];
										}

										var taskCell  = '<a class="task-link" href="'+url+'"  target="_blank" data-toggle="popover" data-trigger="hover" title="'+title+'" data-content="'+content+'">';
											taskCell += '<div class="task-cell" style="background-color:'+bg+'"></div>';
											taskCell += '</a>';

										$('#row-'+users[i].phid).find('.day-'+days[n]).append(taskCell);
									}
								}
							}
						}

						$('.task-link').popover();
					}
				});
			});
		}
	}

})(jQuery);