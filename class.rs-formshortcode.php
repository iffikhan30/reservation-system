<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Rs_Formshortcode{
    public function __construct(){

        add_shortcode('reservation_form', array($this,'reservaltion_system_contactform'));
        add_shortcode('reservation_result', array($this,'reservation_system_showeventdate'));

    }
    public function reservaltion_system_contactform(){
        global $post;
            if(is_single()) {?>
                <form id="reservationformid" method="post"></br>
                    <?php
                if (function_exists('wp_nonce_field')) {
                    wp_nonce_field('r_security_nonce', 'r_security_nonce');
                }?>
                    <input value="<?php echo $post->ID; ?>" type="hidden" name="r_postid" />
                    <input type="text" placeholder="Event Name" required="required" name="r_eventname" class="form-control"/></br>
                    <textarea placeholder="Event Detail" required="required" name="r_eventdetail" class="form-control"></textarea></br>
                    <input type="text" placeholder="Name" required="required" name="r_name" class="form-control"/></br>
                    <input type="number" min="1" name="r_number" required="required" placeholder="Number" class="form-control"/></br>
                    <input type="text" name="r_date" required="required" placeholder="Date Time" class="datepicker form-control"/></br>
                    <input type="submit" class="btn btn-xs"/>
                    <img id="image" src="<?php echo plugin_dir_url(__FILE__); ?>img/gif.gif" style="display:none;" />
                    <div class="clearfix"></div>
                    <div class="messageshow"></div>
                </form>
                <?php
            }
    }

    public function reservation_system_showeventdate(){
        global $post;
        if(is_single()) {
            $index_query = new WP_Query(array('post_type' => 'reservation', 'post_status' => 'draft',
                'meta_query' => array(
                    array(
                        'key' => 'reservation_postid', // name of custom field
                        'value' => $post->ID,
                        'compare' => '='
                    ),
                )));
            if ($index_query->have_posts()) : ?>
                <div class="calendar">
                    <table>
                        <thead>
                        <tr>
                            <?php
                            if (date('D', mktime(0, 0, 0, date('m'), 1)) == 'Mon') {
                                ?>
                                <td>Mon</td>
                                <td>Tue</td>
                                <td>Wed</td>
                                <td>Thu</td>
                                <td>Fri</td>
                                <td>Sat</td>
                                <td>Sun</td>
                            <?php
                            }
                            if (date('D', mktime(0, 0, 0, date('m'), 1)) == 'Tue') {
                                ?>
                                <td>Tue</td>
                                <td>Wed</td>
                                <td>Thu</td>
                                <td>Fri</td>
                                <td>Sat</td>
                                <td>Sun</td>
                                <td>Mon</td>
                            <?php
                            }
                            if (date('D', mktime(0, 0, 0, date('m'), 1)) == 'Wed') {
                                ?>
                                <td>Wed</td>
                                <td>Thu</td>
                                <td>Fri</td>
                                <td>Sat</td>
                                <td>Sun</td>
                                <td>Mon</td>
                                <td>Tue</td>
                            <?php
                            }
                            if (date('D', mktime(0, 0, 0, date('m'), 1)) == 'Thu') {
                                ?>
                                <td>Thu</td>
                                <td>Fri</td>
                                <td>Sat</td>
                                <td>Sun</td>
                                <td>Mon</td>
                                <td>Tue</td>
                                <td>Wed</td>
                            <?php
                            }
                            if (date('D', mktime(0, 0, 0, date('m'), 1)) == 'Fri') {
                                ?>
                                <td>Fri</td>
                                <td>Sat</td>
                                <td>Sun</td>
                                <td>Mon</td>
                                <td>Tue</td>
                                <td>Wed</td>
                                <td>Thu</td>
                            <?php
                            }
                            if (date('D', mktime(0, 0, 0, date('m'), 1)) == 'Sat') {
                                ?>
                                <td>Sat</td>
                                <td>Sun</td>
                                <td>Mon</td>
                                <td>Tue</td>
                                <td>Wed</td>
                                <td>Thu</td>
                                <td>Fri</td>
                            <?php
                            }
                            if (date('D', mktime(0, 0, 0, date('m'), 1)) == 'Sun') {
                                ?>
                                <td>Sun</td>
                                <td>Mon</td>
                                <td>Tue</td>
                                <td>Wed</td>
                                <td>Thu</td>
                                <td>Fri</td>
                                <td>Sat</td>
                            <?php
                            } ?>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <?php
                            $totaldays = date("t");
                            $i = 1;
                            while ($i <= $totaldays) {
                                echo '<td date-month="' . date('m') . '" date-day="' . $i . '">' . $i . '</td>';
                                echo $i % 7 == 0 ? '</tr><tr>' : '';
                                $i++;
                            }
                            ?>
                        </tr>
                        </tbody>
                    </table>
                    <div class="list">
                        <?php while ($index_query->have_posts()) : $index_query->the_post(); ?>
                            <div class="day-event"
                                 date-month="<?php echo date('m', strtotime(get_post_meta($post->ID, 'reservation_date', 'true'))); ?>"
                                 date-day="<?php echo date('d', strtotime(get_post_meta($post->ID, 'reservation_date', 'true'))); ?>">
                                <a href="javascript:;" class="close fontawesome-remove">X</a>

                                <h2 class="title"><?php the_title(); ?></h2>

                                <p class="date"><?php echo date('Y-m-d', strtotime(get_post_meta($post->ID, 'reservation_date', 'true'))); ?></p>
                                <?php the_content(); ?>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_query(); ?>
                    </div>
                </div>
            <?php
            endif;
        }
    }
}