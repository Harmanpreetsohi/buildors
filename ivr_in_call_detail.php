<?php include_once("header.php");?>



<script src="https://unpkg.com/wavesurfer.js/dist/wavesurfer.js"></script>



<?php 

$noRecording = false;



$sql = "SELECT twillio_call_log.*, users.business_name AS user FROM `twillio_call_log` 

								LEFT JOIN users ON users.id = twillio_call_log.user_id where twillio_call_log.id=".$_GET['id']." order by id desc";

$res = mysqli_query($link,$sql);



	

	$data = mysqli_fetch_assoc($res);



	if (!file_exists('ivr_audio/audio_'.$_GET['id'].'.wav')) {

		$a = file_get_contents($data['recording_url']);

		if ($a) {

			chdir('ivr_audio');

			$f = fopen('audio_'.$_GET['id'].'.wav', 'w+');

			fwrite($f, $a);

			fclose($f);

			chdir('../');

		}

	}

	

	if(trim($data['recording_url'])==''){

		$noRecording = true;

	}



 ?>



<style>



</style>

	<div class="py-2" style="display: flex;" >

		<h3 style="width: 50%;"> Call Detail </h3>
<div class="text-right" style="width: 50%;text-align: end;" >
	<a href="/conversations.php?chat=calllogs" class="btn btn-success" id="save_ivr"><i class="fa fa-arrow-left"></i> Back</a>
</div>
	</div>



	<div class="card" style="min-height: 600px;" >

			<div id="waveform">

				<?php

					if($noRecording){

						echo "<center style='margin-top:20px'><h2>No recording found.</h></center>";

					}

				?>

                <!-- Player -->

            </div>

            <div class="controls">

				<?php

					if(!$noRecording){

				?>

					<div class="row">

						<div class="row px-5 mt-5" > 

							<div class="col-6 " style="color: #4353ff;">

							   <a style="color: #4353ff;" href="<?php echo 'ivr_audio/audio_'.$_GET['id'].'.wav' ?>" download >Download mp3</a>

							</div>

							<div class="col-6 text-end time" style="color: #4353ff;">

							   <p>00:00</p>

							</div>

						 </div>

						<div class="col-12 text-center"  >

							<button class="btn btn-primary" style="height: 70px;width: 70px;border-radius: 100px;" data-action="play">

								<i class="fa fa-play"></i>



								<!-- <i class="fa fa-pause"></i> -->



							</button>

						</div>

					</div>

				<?php 

					}

				?>

            </div>

            <div class="card px-2" style="border: none;">

    <table style="

    width: 350px;

">

        <tbody>

			<tr>

            <th>Number</th>

            <th><p style="margin: 0px;"><?php echo $data['number'] ?></p></th>

            

        </tr>

        <tr>

            <th>Duration</th>

            <th> <p style="margin: 0px;" class="callDuration" ></p> </th>

            

        </tr>

        <tr>

            <th>Date</th>

            <th><p style="margin: 0px;"><?php echo date('M d h:i A',strtotime($data['created_at'])) ?></p></th>

            

        </tr>

        <tr>

            <th>Caller City:</th>

            <th><p style="margin: 0px;"><?php echo $data['CallerCity'] ?></p></th>

            

        </tr>

        <tr>

            <th>Caller Country:</th>

            <th><p style="margin: 0px;"><?php echo $data['CallerCountry'] ?></p></th>

            

        </tr>

        <tr>

            <th>Caller State:</th>

            <th><p style="margin: 0px;"><?php echo $data['CallerState'] ?></p></th>

            

        </tr>

    </tbody></table>

</div>

	</div>

<?php

	if(!$noRecording){

?>

	<script type="text/javascript">

		let wavesurfer = {};

		// Init & load audio file

		document.addEventListener('DOMContentLoaded', function() {

    wavesurfer = WaveSurfer.create({

        container: document.querySelector('#waveform'),

        waveColor: '#D9DCFF',

        progressColor: '#4353FF',

        cursorColor: '#4353FF',

        barWidth: 3,

        barRadius: 3,

        cursorWidth: 1,

        height: 200,

        barGap: 3

    });



    wavesurfer.on('error', function(e) {

        console.warn(e);

    });



    // Load audio from URL

	// wavesurfer.load('<?php //echo 'ivr_audio/audio_'.$_GET['id'].'.wav' ?>');
	wavesurfer.load('<?php echo $data['recording_url'] ?>');


    // Play button

    const button = document.querySelector('[data-action="play"]');



    button.addEventListener('click', wavesurfer.playPause.bind(wavesurfer));



    setInterval(function(){

		if (wavesurfer.isPlaying()) {

			$('[data-action="play"] i').attr('class','fa fa-pause')

		}  else {

			$('[data-action="play"] i').attr('class','fa fa-play')

		}  	

		let sec = wavesurfer.getCurrentTime().toFixed(0);

		if (sec < 10) { sec = '0'+sec }

		$('.time').text('00:'+sec)

		$('.callDuration').text('00:'+parseInt(wavesurfer.getDuration())+'s')

    },1000);



});

   </script>

<?php

	}

?>

	

	

<?php 

	 include_once("footer.php");

?>

<!--<script src="./jquery.nicescroll.min.js"></script>-->

