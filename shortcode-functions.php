<?php 
// Shortcode to display course schedule on frontend
function course_schedule_shortcode() {
    $response_ary = get_option('course_schedule', array());

    $html = '';

    if (!empty($response_ary)) {
        foreach ($response_ary as $response_ar) {
            $scheduleItems = $response_ar->scheduleItems;

            if (!empty($scheduleItems)) {
                $html .= '<h4  class="course_shedule_q_head" style="line-height: 1 !important;">' . esc_html($response_ar->name) . '</h4>';
                $html .= '<table class="course-schedule-table">
                          <thead style="background-color:#f3f3f3 ; color:#e22c38; " class="table-head">
                              	<tr class="sl-tr">
                                    <th class="sl-th">Days</th>
                                    <th>Dates</th>
                                    <th>Time</th>
                                    <th>Hours</th>
                                </tr>
                            </thead>
                            <tbody style="text-align:left;">';

                foreach ($scheduleItems as $scheduleItem) {
                    $html .= '<tr>
								<td class="column" style=" width: auto !important;">
								<input class="course_shedule_q" type="radio" name="date" value="Days: '.$scheduleItem->days.' | Dates: '.$scheduleItem->dates.' " checked >
								<span>'.$scheduleItem->days.'</span>
								</td>
                                <td class="column" style=" width: auto !important;">' . esc_html($scheduleItem->dates) . '</td> 
                                <td class="column" style=" width: auto !important;">' . esc_html($scheduleItem->time) . '</td>
                                <td class="column" style=" width: auto !important;">' . esc_html($scheduleItem->hours) . '</td>
                            </tr>';
                }

                $html .= '</tbody></table>';
            }
        }
    } else {
        $html = '<p>No courses available.</p>';
    }

    return $html;
}

// Register the shortcode
add_shortcode('course_schedule', 'course_schedule_shortcode');
?>
