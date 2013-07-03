<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RTMediaActivity
 *
 * @author saurabh
 */
class RTMediaActivity {


	var $media = array();
	var $activity_text = '';
	var $privacy;

	/**
	 *
	 */
	function __construct($media, $privacy=0, $activity_text=false) {
		if(!isset($media))
			return false;
		if(!is_array($media))
			$media = array($media);

		$this->media = $media;
		$this->activity_text = $activity_text;
		$this->privacy = $privacy;
	}

	function create_activity_html(){


		$html = '';

		$html .='<div class="rtmedia-activity-container">';

			if(!empty($this->activity_text)) {
				$html .= '<span class="rtmedia-activity-text">';
					$html .= $this->activity_text;
				$html .= '</span>';
			}

			$mediaObj = new RTMediaModel();
			$media_details = $mediaObj->get(array('id'=> $this->media));

			$html .= '<ul class="rtmedia-list large-block-grid-5">';
			foreach ($media_details as $media) {
				$html .= '<li class="rtmedia-list-item">';
					$html .= '<div class="rtmedia-item-thumbnail">';
						$html .= '<a href ="'. get_rtmedia_permalink($media->id) .'">';
							$html .= $this->media($media);
						$html .= '</a>';
					$html .= '</div>';

					$html .= '<div class="rtmedia-item-title">';
						$html .= '<h4 title="'. $media->media_title .'">';

							$html .= '<a href="'. get_rtmedia_permalink($media->id) .'">';

								$html .= $media->media_title;
							$html .= '</a>';
						$html .= '</h4>';
					$html .= '</div>';

					$html .= '<div class="rtmedia-item-actions">';
						$html .= $this->actions();
					$html .= '</div>';
				$html .= '</li>';
			}
			$html .= '</ul>';
		$html .= '</div>';
		return $html;
	}

        function actions(){

        }
	function media($media) {
		if (isset($media->media_type)) {
			if ($media->media_type == 'album' ||
					$media->media_type != 'photo') {
				$thumbnail_id = get_rtmedia_meta($media->media_id,'cover_art');
                                if ( $thumbnail_id ) {
                                    list($src, $width, $height) = wp_get_attachment_image_src($thumbnail_id);
                                    return '<img src="'.$src.'" />';
                                }
			} elseif ( $media->media_type == 'photo' ) {
				$thumbnail_id = $media->media_id;
                                if ( $thumbnail_id ) {
                                    list($src, $width, $height) = wp_get_attachment_image_src($thumbnail_id);
                                    return '<img src="'.$src.'" />';
                                }
			} elseif ( $media->media_type == 'video' )  {
				return '<video src="' . wp_get_attachment_url($media->media_id) . '" width="320" height="240" type="video/mp4" class="wp-video-shortcode" id="bp_media_video_' . $media->id . '" controls="controls" preload="none"></video>';
			} elseif ( $media->media_type == 'music' )  {
                                return '<audio src="' . wp_get_attachment_url($media->media_id) . '" width="320" height="0" type="audio/mp3" class="wp-audio-shortcode" id="bp_media_audio_' . $media->id . '" controls="controls" preload="none"></audio>';
			} else  {
				return false;
			}
		} else {
			return false;
		}
	}
}

?>
