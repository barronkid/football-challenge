<?php
class MessengerController extends FootballChallengeController
{
	static function show() {
		$db = option('db_con');
		$log = option('log');
		$output = new StdClass;
		$output->messages = array();
		$query = 'SELECT m.mid AS mid, m.pid AS pid, m.message AS message, m.posted AS posted, u.username AS username, m.uid AS uid FROM junkies_messages m LEFT JOIN junkies_users u ON m.uid = u.uid WHERE m.active = 1 ORDER BY m.pid, m.posted DESC';
		
		if ($results = $db->query($query)) {
			while ($obj = $results->fetch_object()) {
				$output->messages[] = $obj;
			}
		}
		else {
			$log->log('error', 'Could not get messeges.', $db->error);	
		}
		
		return json_encode($output);
	}
	
	static function create() {
		$db = option('db_con');
		$log = option('log');
		$output = new StdClass;
		// mid, pid, uid, message (blob), posted, active, week (challenge week)
		$query = 'INSERT INTO {{messages}} VALUES (NULL, %d, %s, "%s", %s, %s, %s)';
		$user_info = option('user_info');
		
		if ($user_info['use'] === TRUE) {
			$pid = get_post('id') === 'main' ? NULL : get_post('id');
			$msg = $db->escape_string(strip_tags(get_post('message')));
			$db->setQuery(
				'createMessage',
				$query,
				$pid,
				$user_info['uid'],
				$msg,
				time(),
				1,
				option('challenge_week')
			);
			if ($db->useQuery('createMessage')) {
				$output->success = TRUE;
			}
			else {
				#$log->log('error', 'Could not create a message.', $db->getQuery('createMessage'), $db->error);
				$output->success = FALSE;
			}
		}
		
		header('Content-type: application/json');
		return json_encode($output);
	}
	
	/* COMING SOON */
	
	static function edit($id) {
		return 'Coming soon';
	}
	
	static function delete($id) {
		return 'Coming soon';
	}
}
?>