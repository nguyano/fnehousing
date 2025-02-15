<?php
/**
 * Shelter Working Hourse Form Template
 *
 * @version   1.0.0
 * @package   Fnehousing
 */
 
defined('ABSPATH') || exit;

$days = [__('Monday', 'fnehousing'), __('Tuesday', 'fnehousing'), __('Wednesday', 'fnehousing'), __('Thursday', 'fnehousing'), __('Friday', 'fnehousing'), __('Saturday', 'fnehousing'), __('Sunday', 'fnehousing')];
$time_slots = ['6:00 AM', '7:00 AM', '8:00 AM', '9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM',
   '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM', '6:00 PM', '7:00 PM', '8:00 PM', '9:00 PM', '10:00 PM', '11:00 PM', '12:00 AM'];
   ob_start(); ?>
			<table class="table table-bordered table-striped">
				<thead class="thead-light">
					<tr>
						<th><?= __('Day', 'fnehousing'); ?></th>
						<th><?= __('Start Time', 'fnehousing'); ?></th>
						<th><?= __('End Time', 'fnehousing'); ?></th>
						<th class="text-center"><?= __('Off', 'fnehousing'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($days as $day): ?>
						<tr>
							<td><?php echo $day; ?></td>
							<td>
								<select name="start[<?= $day; ?>]" class="form-control">
									<?php foreach ($time_slots as $time): ?>
										<option value="<?= $time; ?>"><?php echo $time; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
							<td>
								<select name="end[<?= $day; ?>]" class="form-control">
									<?php foreach ($time_slots as $time): ?>
										<option value="<?= $time; ?>"><?php echo $time; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
							<td class="text-center">
								<input type="checkbox" name="off[<?= $day; ?>]" value="off" class="form-check-input">
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			
	<?= ob_get_clean();

