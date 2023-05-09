<?php
session_start();
date_default_timezone_set('UTC');
include ("dbconfig.php");

$sql = "SELECT id,parent,guid,parent_guid,text FROM gantt_tasks WHERE programme_id='156'";
$stmt = $db->prepare($sql);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tasks_copy = $tasks;

foreach ($tasks as $task)
{
	echo "working on " . $task['text'];
	$parent = $task['parent'];
	$task_id = $task['id'];
	$task_text = $task['text'];
		
	
	
	foreach ($tasks_copy as $task_copy)
	{
		if ($task_copy['id'] == $parent)
		{
			$parent_found = $task_copy['guid'];
			Echo "found parent guid of " . $parent_found;
			
			if ($task_text == "Preliminaries")
			{
				$sql = "UPDATE gantt_tasks SET parent_guid='$parent_found' WHERE id='$task_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}
		}
	}

	

	
}

echo "done";


?>