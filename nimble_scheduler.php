<?php
	include_once("header.php");
?>
<link href="./fullcalendar/css/fullcalendar.min.css" rel="stylesheet" />
<script>
	var events = [];
</script>
<?php
    $sql = "select * from schedulers";
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)){
		$index = 0;
		while($row = mysqli_fetch_assoc($res)){
            ?>
            <script>
				var event = {};
				event['id'] = '<?php echo $row["id"]; ?>';
				event['title'] = '<?php echo $row["event_title"]; ?>';
				event['start'] = '<?php echo $row["end_date"]; ?>';
				events['<?php echo $index; ?>'] = event;
            </script>
            <?php
            $index++;
        }
    }
?>
	<div class="py-2">
		<h3>SMS Scheduler</h3>
	</div>

	<div class="card">
		<div class="card-body">
			<div class="col-lg-12 col-sm-12">
				<div id="calendar" class="calender_style"></div>
			</div>
		</div>
	</div>
	<div class="modal fade none-border" id="event-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="server.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add New Event</h4>
                </div>
                <div class="modal-body p-20">
						<div class="form-group">
							<label>Title*</label>
							<input type="text" name="title" parsley-trigger="change" required placeholder="Enter title..." class="form-control">
						</div>
						<div class="form-group">
							<label>Date*</label>
							<input type="text" class="form-control addDatePicker z-index" name="date" required >
						</div>
						<div class="form-group">
							<label>Time*</label>
							<select name="time" class="form-control" parsley-trigger="change" required>
							<?php
								$time = getTimeArray();
								foreach($time as $key => $value){ ?>
									<option <?php echo DBout($selected)?> value="<?php echo DBout($key)?>"><?php echo DBout($value)?></option>
							<?php	}
							?>
							</select>
						</div>
						<div class="form-group">
							<label><input name="send_immediate" value="1" type="checkbox" /> Send Immediate</label>
						</div>
                        <div class="form-group">
							<label><input name="attach_mobile_device" value="1" type="checkbox" /> Attach mobile device</label>
						</div>
						<div class="form-group">
							<label>Select Group</label>
							<select class="form-control" name="group_id" onChange="getGroupNumbers(this.value)" parsley-trigger="change" required>
								<option value="">- Select One -</option>
							<?php
								$sqlg = sprintf("select id, title from campaigns where user_id=%s",
                                        mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
                                    );
								$resg = mysqli_query($link,$sqlg);
								if(mysqli_num_rows($resg)){
									while($rowg = mysqli_fetch_assoc($resg)){ ?>
										<option <?php echo DBout($sel)?> value="<?php echo DBout($rowg['id'])?>"><?php echo DBout($rowg['title'])?></option>
							<?php		}
								}else{ ?>
									<option value="">No group found</option>
							<?php	}
							?>
							</select>
						</div>
						<div class="form-group">
							<label>Select Number</label>
							<select name="phone_number" class="form-control" id="list_group_number"></select>
						</div>
						<div class="form-group">
							<label>Message</label>
							<textarea name="message" class="form-control textCounter" parsley-trigger="change" required></textarea>
							<span class="showCounter">
								<span class="showCount"><?php echo DBout($maxLength)?></span> Characters left
							</span>
						</div>
						<div class="form-group">
							<label>Media</label>
							<input type="file" name="media">
						</div>
                        <div class="form-group" id="media_area"></div>


						<div class="form-group text-right m-b-0 display-none">
							<button class="btn btn-primary waves-effect waves-light" type="submit"> Save </button>
							<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
						</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success save-event waves-effect waves-light">Save</button>

                    <input type="hidden" name="hidden_media" value="" />
                    <input type="hidden" name="cmd" value="save_scheduler" />
                    <input type="hidden" name="scheduler_id" value="0" />
                </div>
            </form>
            </div>
        </div>
    </div>

    <div class="modal fade none-border z-index" id="add-category">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add a category MODALLLLLLLL</h4>
                </div>
                <div class="modal-body p-20">
                    <form role="form">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Category Name</label>
                                <input class="form-control form-white" placeholder="Enter name" type="text" name="category-name"/>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Choose Category Color</label>
                                <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                    <option value="success">Success</option>
                                    <option value="danger">Danger</option>
                                    <option value="info">Info</option>
                                    <option value="pink">Pink</option>
                                    <option value="primary">Primary</option>
                                    <option value="warning">Warning</option>
                                    <option value="orange">Orange</option>
                                    <option value="brown">Brown</option>
                                    <option value="teal">Teal</option>
                                    <option value="inverse">Inverse</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light save-category" data-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
<?php include_once("footer.php");?>
<script>
	"use strict";
	!function($) {
		var CalendarApp = function() {
			this.$body = $("body")
			this.$modal = $('#event-modal'),
				this.$event = ('#external-events div.external-event'),
				this.$calendar = $('#calendar'),
				this.$saveCategoryBtn = $('.save-category'),
				this.$categoryForm = $('#add-category form'),
				this.$extEvents = $('#external-events'),
				this.$calendarObj = null
		};

		CalendarApp.prototype.onDrop = function (eventObj, date) {
			var $this = this;
			var originalEventObject = eventObj.data('eventObject');
			var $categoryClass = eventObj.attr('data-class');
			var copiedEventObject = $.extend({}, originalEventObject);
			copiedEventObject.start = date;
			if ($categoryClass)
				copiedEventObject['className'] = [$categoryClass];
			$this.$calendar.fullCalendar('renderEvent', copiedEventObject, true);
			if ($('#drop-remove').is(':checked')) {
				eventObj.remove();
			}
		},

			CalendarApp.prototype.onEventClick =  function (calEvent, jsEvent, view) {
				var $this = this;
				$("#list_group_number").removeAttr("multiple");
				$this.$modal.find('.modal-title').html("Edit "+calEvent.title+" Event");
				$this.$modal.find('input[name=scheduler_id]').val(calEvent.id);
				$this.$modal.find('input[name=title]').val(calEvent.title);
				$this.$modal.find('select[name=group_id]').val(calEvent.group_id);
				$this.$modal.find('textarea[name=message]').val(calEvent.message);
				$this.$modal.find('input[name=date]').val(calEvent.start.format("YYYY-MM-DD"));
				$this.$modal.find('select[name=time]').val(calEvent.start.format("HH:mm"));
				if(calEvent.attach_mobile_device==1){
					$this.$modal.find('input[name=attach_mobile_device]').attr("checked",true);
				}
				if(calEvent.send_immediate==1){
					$this.$modal.find('input[name=send_immediate]').attr("checked",true);
				}
				$this.$modal.find('input[name=cmd]').val("update_scheduler");
				if(calEvent.phone_number.includes(",")){
					$("#list_group_number").attr("multiple","multiple");
				}
				if(calEvent.media!=""){
					$this.$modal.find('div[id=media_area]').html("<img src='"+calEvent.media+"' width='100px' height='100px' >");
					$this.$modal.find('input[name=hidden_media]').val(calEvent.media);
				}

				getGroupNumbers(calEvent.group_id,calEvent.phone_number);
				$this.$modal.modal({
					backdrop: 'static'
				});

				return false;

				var form = $("<form></form>");
				form.append("<label>Change event name</label>");
				form.append("<div class='input-group'><input class='form-control' type=text value='" + calEvent.title + "' /><span class='input-group-btn'><button type='submit' class='btn btn-success waves-effect waves-light'><i class='fa fa-check'></i> Save</button></span></div>");
				$this.$modal.modal({
					backdrop: 'static'
				});
				$this.$modal.find('.delete-event').show().end().find('.save-event').hide().end().find('.modal-body').empty().prepend(form).end().find('.delete-event').unbind('click').click(function () {
					$this.$calendarObj.fullCalendar('removeEvents', function (ev) {
						return (ev._id == calEvent._id);
					});
					$this.$modal.modal('hide');
				});
				$this.$modal.find('form').on('submit', function () {
					calEvent.title = form.find("input[type=text]").val();
					$this.$calendarObj.fullCalendar('updateEvent', calEvent);
					$this.$modal.modal('hide');
					return false;
				});
			},


			CalendarApp.prototype.onSelect = function (start, end, allDay) {
				var $this = this;
				$("#list_group_number").removeAttr("multiple");
				$("#list_group_number").html("");

				$this.$modal.find('.modal-title').html("Add New Event");
				$this.$modal.find('input[name=scheduler_id]').val("0");
				$this.$modal.find('input[name=title]').val("");
				$this.$modal.find('select[name=group_id]').val("");
				$this.$modal.find('select[name=phone_number]').val("");
				$this.$modal.find('textarea[name=message]').val("");
				$this.$modal.find('input[name=date]').val("");
				$this.$modal.find('select[name=time]').val("");
				$this.$modal.find('input[name=attach_mobile_device]').attr("checked",false);
				$this.$modal.find('input[name=send_immediate]').attr("checked",false);
				$this.$modal.find('input[name=cmd]').val("save_scheduler");

				$this.$modal.find('div[id=media_area]').html("");
				$this.$modal.find('input[name=hidden_media]').val("");
				$this.$modal.find('input[name=media]').val("");

				$this.$modal.modal({
					backdrop: 'static'
				});

				return false;


				var form = $("<form></form>");
				form.append("<div class='row'></div>");
				form.find(".row")
					.append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Event Name</label><input class='form-control' placeholder='Insert Event Name' type='text' name='title'/></div></div>")
					.append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Category</label><select class='form-control' name='category'></select></div></div>")
					.find("select[name='category']")
					.append("<option value='bg-danger'>Danger</option>")
					.append("<option value='bg-success'>Success</option>")
					.append("<option value='bg-purple'>Purple</option>")
					.append("<option value='bg-primary'>Primary</option>")
					.append("<option value='bg-pink'>Pink</option>")
					.append("<option value='bg-info'>Info</option>")
					.append("<option value='bg-inverse'>Inverse</option>")
					.append("<option value='bg-orange'>Orange</option>")
					.append("<option value='bg-brown'>Brown</option>")
					.append("<option value='bg-teal'>Teal</option>")
					.append("<option value='bg-warning'>Warning</option></div></div>");
				$this.$modal.find('.delete-event').hide().end().find('.save-event').show().end().find('.modal-body').empty().prepend(form).end().find('.save-event').unbind('click').click(function () {
					form.submit();
				});
				$this.$modal.find('form').on('submit', function () {
					var title = form.find("input[name='title']").val();
					var beginning = form.find("input[name='beginning']").val();
					var ending = form.find("input[name='ending']").val();
					var categoryClass = form.find("select[name='category'] option:checked").val();
					if (title !== null && title.length != 0) {
						$this.$calendarObj.fullCalendar('renderEvent', {
							title: title,
							start:start,
							end: end,
							allDay: false,
							className: categoryClass
						}, true);
						$this.$modal.modal('hide');
					}
					else{
						alert('You have to give a title to your event');
					}
					return false;

				});
				$this.$calendarObj.fullCalendar('unselect');
			},
			CalendarApp.prototype.enableDrag = function() {
				$(this.$event).each(function () {
					var eventObject = {
						title: $.trim($(this).text())
					};
					$(this).data('eventObject', eventObject);
					$(this).draggable({
						zIndex: 999,
						revert: true,
						revertDuration: 0
					});
				});
			}
		CalendarApp.prototype.init = function() {
			this.enableDrag();
			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
			var form = '';
			var today = new Date($.now());

			var defaultEvents = events;

			var $this = this;
			$this.$calendarObj = $this.$calendar.fullCalendar({
				slotDuration: '00:15:00',
				minTime: '08:00:00',
				maxTime: '19:00:00',
				defaultView: 'month',
				handleWindowResize: true,
				height: $(window).height() - 200,
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				events: defaultEvents,
				editable: true,
				droppable: true,
				eventLimit: true,
				selectable: true,
				drop: function(date) { $this.onDrop($(this), date); },
				select: function (start, end, allDay) { $this.onSelect(start, end, allDay); },
				eventClick: function(calEvent, jsEvent, view) { $this.onEventClick(calEvent, jsEvent, view); }

			});

			this.$saveCategoryBtn.on('click', function(){
				var categoryName = $this.$categoryForm.find("input[name='category-name']").val();
				var categoryColor = $this.$categoryForm.find("select[name='category-color']").val();
				if (categoryName !== null && categoryName.length != 0) {
					$this.$extEvents.append('<div class="relative external-event bg-' + categoryColor + '" data-class="bg-' + categoryColor + '"><i class="mdi mdi-checkbox-blank-circle m-r-10 vertical-middle"></i>' + categoryName + '</div>')
					$this.enableDrag();
				}

			});
		},
			$.CalendarApp = new CalendarApp, $.CalendarApp.Constructor = CalendarApp

	}(window.jQuery),

		function($) {
			"use strict";
			$.CalendarApp.init()
		}(window.jQuery);

	function getGroupNumbers(groupID,numberID){
		var Qry = 'cmd=get_group_numbers&group_id='+groupID+'&numberID='+numberID;
		$.post('server.php',Qry,function(r){
			$('#list_group_number').html(r);
		});
	}
	function deleteScheduler(id,img){
		if(confirm("Are you sure you want to delete this schduler?")){
			window.location = 'server.php?cmd=delete_scheduler&id='+id;
		}
	}
</script>