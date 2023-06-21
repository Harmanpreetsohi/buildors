<!--
<footer class="bg-white rounded shadow p-5 mb-4 mt-4">
	<div class="row">
		<div class="col-12 col-md-4 col-xl-6 mb-4 mb-md-0">
			<p class="mb-0 text-center text-lg-start">© <span class="current-year"></span> <a class="text-primary fw-normal" href="https://wanamakergroup.com" target="_blank">info@wanamakergroup.com</a>
			</p>
		</div>
		<div class="col-12 col-md-8 col-xl-6 text-center text-lg-start">

		</div>
	</div>
</footer>
-->
</main>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<!-- Core --> 
<script src="./vendor/@popperjs/core/dist/umd/popper.min.js"></script>
<script src="./vendor/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Vendor JS --> 
<script src="./vendor/onscreen/dist/on-screen.umd.min.js"></script>

<!-- Slider --> 
<script src="./vendor/nouislider/distribute/nouislider.min.js"></script>

<!-- Smooth scroll --> 
<script src="./vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

<!-- Count up --> 
<script src="./vendor/countup.js/dist/countUp.umd.js"></script>

<!-- Apex Charts --> 
<script src="./vendor/apexcharts/dist/apexcharts.min.js"></script>

<!-- Datepicker --> 
<script src="./vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

<!-- DataTables --> 
<script src="./vendor/simple-datatables/dist/umd/simple-datatables.js"></script>

<!-- Sweet Alerts 2 --> 
<script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>

<!-- Moment JS --> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>

<!-- Vanilla JS Datepicker --> 
<script src="./vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

<!-- Full Calendar --> 
<script src="./vendor/fullcalendar/main.min.js"></script>

<!-- Dropzone --> 
<script src="./vendor/dropzone/dist/min/dropzone.min.js"></script>

<!-- Choices.js --> 
<script src="./vendor/choices.js/public/assets/scripts/choices.min.js"></script>

<!-- Notyf --> 
<script src="./vendor/notyf/notyf.min.js"></script>

<!-- Mapbox & Leaflet.js --> 
<script src="./vendor/leaflet/dist/leaflet.js"></script>

<!-- SVG Map --> 
<script src="./vendor/svg-pan-zoom/dist/svg-pan-zoom.min.js"></script>
<script src="./vendor/svgmap/dist/svgMap.min.js"></script>

<!-- Simplebar --> 
<script src="./vendor/simplebar/dist/simplebar.min.js"></script>

<!-- Sortable Js --> 
<script src="./vendor/sortablejs/Sortable.min.js"></script>

<!-- Github buttons --> 
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Volt JS --> 
<script src="./js/volt.js"></script>


<div class="modal fade" id="dialer_modal" tabindex="-1" aria-labelledby="dialer_modal_label" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="dialer_modal_label">Dialer</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table id="dialer_table">
					<tr>
						<td id="dialer_input_td" colspan="3">
							<input name="dialer_number" id="dialer_number" type="number" placeholder="3212345625" class="form-control">
						</td>
					</tr>
					<tr class="dialer_num_tr">
						<td class="dialer_num" onclick="dialerClick('dial', 1)">1</td>
						<td class="dialer_num" onclick="dialerClick('dial', 2)">2</td>
						<td class="dialer_num" onclick="dialerClick('dial', 3)">3</td>
					</tr>
					<tr class="dialer_num_tr">
						<td class="dialer_num" onclick="dialerClick('dial', 4)">4</td>
						<td class="dialer_num" onclick="dialerClick('dial', 5)">5</td>
						<td class="dialer_num" onclick="dialerClick('dial', 6)">6</td>
					</tr>
					<tr class="dialer_num_tr">
						<td class="dialer_num" onclick="dialerClick('dial', 7)">7</td>
						<td class="dialer_num" onclick="dialerClick('dial', 8)">8</td>
						<td class="dialer_num" onclick="dialerClick('dial', 9)">9</td>
					</tr>
					<tr class="dialer_num_tr">
						<td class="dialer_del_td">
							<img alt="clear" onclick="dialerClick('clear', 'clear')" src="data:image/svg+xml;base64,PHN2ZyBhcmlhLWhpZGRlbj0idHJ1ZSIgZm9jdXNhYmxlPSJmYWxzZSIgZGF0YS1wcmVmaXg9ImZhcyIgZGF0YS1pY29uPSJlcmFzZXIiIHJvbGU9ImltZyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiIgY2xhc3M9InN2Zy1pbmxpbmUtLWZhIGZhLWVyYXNlciBmYS13LTE2IGZhLTd4Ij48cGF0aCBmaWxsPSIjYjFiMWIxIiBkPSJNNDk3Ljk0MSAyNzMuOTQxYzE4Ljc0NS0xOC43NDUgMTguNzQ1LTQ5LjEzNyAwLTY3Ljg4MmwtMTYwLTE2MGMtMTguNzQ1LTE4Ljc0NS00OS4xMzYtMTguNzQ2LTY3Ljg4MyAwbC0yNTYgMjU2Yy0xOC43NDUgMTguNzQ1LTE4Ljc0NSA0OS4xMzcgMCA2Ny44ODJsOTYgOTZBNDguMDA0IDQ4LjAwNCAwIDAgMCAxNDQgNDgwaDM1NmM2LjYyNyAwIDEyLTUuMzczIDEyLTEydi00MGMwLTYuNjI3LTUuMzczLTEyLTEyLTEySDM1NS44ODNsMTQyLjA1OC0xNDIuMDU5em0tMzAyLjYyNy02Mi42MjdsMTM3LjM3MyAxMzcuMzczTDI2NS4zNzMgNDE2SDE1MC42MjhsLTgwLTgwIDEyNC42ODYtMTI0LjY4NnoiIGNsYXNzPSIiPjwvcGF0aD48L3N2Zz4=" width="22px" title="Clear" />
						</td>
						<td class="dialer_num" onclick="dialerClick('dial', 0)">0</td>
						<td class="dialer_del_td">
							<img alt="delete" onclick="dialerClick('delete', 'delete')" src="data:image/svg+xml;base64,PHN2ZyBhcmlhLWhpZGRlbj0idHJ1ZSIgZm9jdXNhYmxlPSJmYWxzZSIgZGF0YS1wcmVmaXg9ImZhciIgZGF0YS1pY29uPSJiYWNrc3BhY2UiIHJvbGU9ImltZyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgNjQwIDUxMiIgY2xhc3M9InN2Zy1pbmxpbmUtLWZhIGZhLWJhY2tzcGFjZSBmYS13LTIwIGZhLTd4Ij48cGF0aCBmaWxsPSIjREMxQTU5IiBkPSJNNDY5LjY1IDE4MS42NWwtMTEuMzEtMTEuMzFjLTYuMjUtNi4yNS0xNi4zOC02LjI1LTIyLjYzIDBMMzg0IDIyMi4wNmwtNTEuNzItNTEuNzJjLTYuMjUtNi4yNS0xNi4zOC02LjI1LTIyLjYzIDBsLTExLjMxIDExLjMxYy02LjI1IDYuMjUtNi4yNSAxNi4zOCAwIDIyLjYzTDM1MC4wNiAyNTZsLTUxLjcyIDUxLjcyYy02LjI1IDYuMjUtNi4yNSAxNi4zOCAwIDIyLjYzbDExLjMxIDExLjMxYzYuMjUgNi4yNSAxNi4zOCA2LjI1IDIyLjYzIDBMMzg0IDI4OS45NGw1MS43MiA1MS43MmM2LjI1IDYuMjUgMTYuMzggNi4yNSAyMi42MyAwbDExLjMxLTExLjMxYzYuMjUtNi4yNSA2LjI1LTE2LjM4IDAtMjIuNjNMNDE3Ljk0IDI1Nmw1MS43Mi01MS43MmM2LjI0LTYuMjUgNi4yNC0xNi4zOC0uMDEtMjIuNjN6TTU3NiA2NEgyMDUuMjZDMTg4LjI4IDY0IDE3MiA3MC43NCAxNjAgODIuNzRMOS4zNyAyMzMuMzdjLTEyLjUgMTIuNS0xMi41IDMyLjc2IDAgNDUuMjVMMTYwIDQyOS4yNWMxMiAxMiAyOC4yOCAxOC43NSA0NS4yNSAxOC43NUg1NzZjMzUuMzUgMCA2NC0yOC42NSA2NC02NFYxMjhjMC0zNS4zNS0yOC42NS02NC02NC02NHptMTYgMzIwYzAgOC44Mi03LjE4IDE2LTE2IDE2SDIwNS4yNmMtNC4yNyAwLTguMjktMS42Ni0xMS4zMS00LjY5TDU0LjYzIDI1NmwxMzkuMzEtMTM5LjMxYzMuMDItMy4wMiA3LjA0LTQuNjkgMTEuMzEtNC42OUg1NzZjOC44MiAwIDE2IDcuMTggMTYgMTZ2MjU2eiIgY2xhc3M9IiI+PC9wYXRoPjwvc3ZnPg==" width="25px" title="Delete" />
						</td>
					</tr>
					<tr>
						<td colspan="3"><a href="javascript:void(0)" type="button" id="dialer_call_btn_td" onClick="alert('Under development')">Call</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<script>
	function getPipelineStages(obj,elementID){
		var pipelineID = $(obj).val();
		jQuery.ajax({
			type : "post",
			dataType : "json",
			url : "server.php",
			data : {cmd: "get_pipeline_stages", pipeline_id: pipelineID},
			success: function(response){
				if(response){
					$('#'+elementID).html(response);
				}
			}
		});
	}
	
	function get_permission(ID){
	    var id = ID;
	    $("#u_id").val(id);
		jQuery.ajax({
			type : "post",
			url : "server.php",
			data : {cmd: "get_user_permissions", id: id},
			success: function(response){
			    console.log(response)
				if(response){
				    $('.all_module_permissions').html(response);
				}
			}
		});
	}
	
	$(document).on('click', '.check_permit', function () {
    	let id = $(this).attr('id');
        let perm_type = $(this).attr('per_type');
        let status = $(this).attr('status');
        if(status==0){
            $(this).attr('status','1')
        }else{
            $(this).attr('status','0')
        }
        $.ajax({
            type: "post",
            url: "server.php",
            data: {
                'cmd':'update_permission',
                'id':id,
                'perm_type':perm_type,
                'status':status,
            },
            success: function (data) {
                console.log(data);
            }
        });
	});
	
	function dialerClick(type, value) {
		let input = $('#dialer_input_td input');
		let input_val = $('#dialer_input_td input').val();
		if (type == 'dial') {
			input.val(input_val + value);
		} else if (type == 'delete') {
			input.val(input_val.substring(0, input_val.length - 1));
		} else if (type == 'clear') {
			input.val("");
		}
	}
	function showDialer(obj,customerNumber=''){
		$("#dialer_number").val('');
		$("#dialer_modal").modal('show');
		if($.trim(customerNumber)!=''){
			$("#dialer_number").val(customerNumber);
		}
	}
	function getCompanyName(obj){
		var companyID = $(obj).val();
		$(".overlay").show();
		$.post("server.php",{"cmd":"switch_company",companyID:companyID},function(r){
			window.location = "dashboard.php";
		});
	}
	function setCookie(cname,cvalue,exdays){
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}
	function getCookie(cname){
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
	function checkCookie(cookieName){
		var username = getCookie(cookieName);
		return username;
		/*
		if(username != ""){
			alert("Welcome again " + username);
		}else{
			username = prompt("Please enter your name:", "");
			if(username != "" && username != null){
				setCookie("username", username, 365);
			}
		}
		*/
	}
	const swalWithBootstrapButtons = Swal.mixin({
		customClass: {
			confirmButton: 'btn btn-primary',
			cancelButton: 'btn btn-gray'
		},
		buttonsStyling: false
	});
</script>