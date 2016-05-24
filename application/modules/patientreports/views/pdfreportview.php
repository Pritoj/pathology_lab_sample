<html>
	<head>
	</head>
	<body>

		<div class="container">
		    <div class="panel panel-default">
		        <div class="panel-heading">
		            <h1 class="panel-title">
		                Report for <?=$reportDetails['patientFirstName'];?> <?=$reportDetails['patientLastName'];?> by <?=$reportDetails['doctorName'];?>
		            </h1>
		        </div>
		        <div class="panel-body">
		            <div class="well">
		                <h3>
		                    Diagnosis
		                </h3>
		                <p>
		                    <?=$reportDetails['diagnosis'];?>
		                </p>
		                <hr/>
		                <h3>
		                    Microscopic Exam
		                </h3>
		                <p>
		                    <?=$reportDetails['microscopicExam'];?>
		                </p>
		                <hr/>
		                <h3>
		                    Gross Exam
		                </h3>
		                <p>
		                    <?=$reportDetails['grossExam'];?>
		                </p>
		                <hr/>
		                <h3>
		                    Other Comments
		                </h3>
		                <p>
		                    <?=$reportDetails['otherComments'];?>
		                </p>
		                <hr/>
		            </div>
		        </div>
		        <div>
		            <div class="panel panel-default">
		                <div class="panel-heading">
		                    Tests
		                </div>
		                <div class="panel-body">

		                <?php foreach($reportDetails['tests'] as $test):?>
		                	<h3><?=$test['testTypeName'];?></h3>
		                	<?php foreach($test['fields'] as $field):?>
		                		<p>
		                			<?=$field['testFieldName'];?> - <?=$field['value'];?>
		                		</p>
		                	<?php endforeach;?>
		                <?php endforeach;?>

		                    
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</body>
</html>