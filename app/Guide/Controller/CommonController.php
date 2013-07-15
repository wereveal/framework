<?php
/**
 *  Descriptions.
 *  @file BaseController.php
 *  @class BaseController
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 0.1
 *  @par Change Log
 *      v0.1 - Initial version
 *  @par Wer Guide version 1.0
 *  @date 2013-06-03 14:38:59
 *  @ingroup guide controller
**/
namespace Wer\Guide\Controller;

use Wer\Guide\Model\Category;
use Wer\Guide\Model\Item;
use Wer\Guide\Model\Section;
use Wer\Guide\Model\Entity\QuickSearchEntity as QSEntity;
use Wer\Guide\View\QuickSearch;
use Wer\Framework\Library\Arrays;
use Wer\Framework\Library\Elog;
use Wer\Framework\Library\Strings;
use Twig_Loader_Filesystem;
use Twig_Environment;

abstract class CommonController
{
    protected $default_section = 1;
    protected $num_to_display  = 10;
    protected $phone_format    = "(XXX) XXX-XXXX";
    protected $date_format     = "m/d/Y";

    /**
     *  Creates the values to be used in the twig tpl.
     *  @param str $current_letter optional, defaults to ''
     *  @return array $a_values
    **/
    public function alphaList($current_letter = '')
    {
        $main_str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $a_li_values  = array();
        $first_letter = $main_str[0];
        $last_letter  = $main_str[count($main_str) - 1];
        for ($i = 0; $i < strlen($main_str); $i++) {
            $the_letter = $main_str[$i];
            $the_class  = 'otherLetter';
            $is_link    = true;
            $results = $this->o_item->readItemByNameFirstLetter($the_letter);
            if ($results != false && count($results) > 0) {
                if ($the_letter == $first_letter) {
                    $the_class = 'firstLetter';
                } elseif ($the_letter == $last_letter) {
                    $the_class = 'lastLetter';
                }
                if ($the_letter == $current_letter) {
                    $the_class .= ' currentLetter';
                    $is_link = false;
                }
            } else {
                $the_class = 'noLink';
                $is_link   = false;
            }
            $a_li_row = array(
                'the_letter' => $the_letter,
                'the_class'  => $the_class,
                'is_link'    => $is_link,
            );
            $a_li_values[$i] = $a_li_row;
        }
        return $a_li_values;
    }
    /**
     *  Creates the values to be used in the category select.
     *  @param int $section_id defaults to 1
     *  @return array $a_categories
    **/
    public function categoryList($section_id = 1, $selected_category = '')
    {
        $a_return_this = array(
            'name'        => '',
            'class'       => '',
            'other_stuph' => '',
            'options'     => '',
            'label_for'   => '',
            'label_text'  => '',
            'label_class' => ''
        );
        $a_categories = $this->o_cat->readCatBySec($section_id);
        if (count($a_categories) > 1) {
            foreach ($a_categories as $a_category) {
                if ($selected_category == '') {
                    $selected_category = $a_category['cat_id'];
                }
                $a_return_this['options'][] = array(
                    'value'       => $a_category['cat_id'],
                    'label'       => $a_category['cat_name'],
                    'other_stuph' => $selected_category == $a_category['sec_id'] ? ' selected' : ''
                );
            }
            $a_return_this['name']        = 'catSelect';
            $a_return_this['class']       = 'catSelect';
            $a_return_this['other_stuph'] = ' id="catSelect" onchange="searchByCategory(this)"';
            $a_return_this['label_for']   = 'catSelect';
            $a_return_this['label_text']  = 'Search By Category';
            $a_return_this['label_class'] = 'selectLabel';
        }
        return $a_return_this;
    }
    /**
     *  Returns the data needed for the quick search form.
     *  @param array $a_search_for
     *  @return array
    **/
    public function formQuickSearch($a_search_for = '')
    {
        if ($a_search_for != '' && is_array($a_search_for)) {
            foreach ($a_search_for as $value) {
                if (strpos($value, ' ') !== false) {
                    $value = '"' . $value . '"';
                }
                $search_str .= $value . ' ';
            }

        } else {
            $search_str = '';
        }
        return array(
            'buttonColor'   => 'white',
            'buttonText'    => 'Locate',
            'searchForText' => $search_str
        );
    }
    /**
     *  creates the values needed for the section list
     *  @param mixed $selected_section can be an id or a name
     *  @param array $a_search_parameters
     *      (see o_sec->readSection comments for more info)
     *  @return array $a_return_this
    **/
    public function sectionList($selected_section = '', $a_search_parameters = '')
    {
        $a_return_this = array(
            'name'        => '',
            'class'       => '',
            'other_stuph' => '',
            'options'     => '',
            'label_for'   => '',
            'label_text'  => '',
            'label_class' => ''
        );
        $a_sections = $this->o_sec->readSection('', $a_search_parameters);
        if (count($a_sections) > 1) {
            foreach ($a_sections as $a_section) {
                if ($selected_section == '') {
                    $selected_section = $a_section['sec_id'];
                }
                $a_return_this['options'][] = array(
                    'value'     => $a_section['sec_id'],
                    'label'     => $a_section['sec_name'],
                    'other_stuph' => $selected_section == $a_section['sec_id'] ? ' selected' : ''
                );
            }
            $a_return_this['name']        = 'sectionSelect';
            $a_return_this['class']       = 'sectionSelect';
            $a_return_this['other_stuph'] = ' id="sectionSelect" onchange="searchBySection(this)"';
            $a_return_this['label_for']   = 'sectionSelect';
            $a_return_this['label_text']  = 'Search By Section';
            $a_return_this['label_class'] = 'selectLabel';
        }
        $this->o_elog->write("select list array\n" . var_export($a_return_this, true), LOG_OFF, __METHOD__ . '.' . __LINE__);
        return $a_return_this;
    }
    /**
     *  Adds item data to the item records
     *  @param array $a_items required
     *  $a_search_for_fields required specific fields by name to search for e.g. array('about', 'phone')
     *  $a_search_parameters optional
     *  @return array $a_items
    **/
    public function addDataToItems($a_items = '', $a_search_for_fields = '', $a_search_parameters = '')
    {
        if ($a_items == '' || $a_search_for_fields == '') {
            return $a_items;
        }
        $this->o_elog->write('' . var_export($a_items, TRUE), LOG_OFF, __METHOD__ . '.' . __LINE__);
        foreach ($a_items as $key => $a_item) {
            foreach ( $a_search_for_fields as $field_name) {
                $a_search_for = array(
                    'data_item_id' => $a_item['item_id'],
                    'field_name' => $field_name
                );
                $a_item_data = $this->o_item->readItemData($a_search_for, $a_search_parameters);
                if (is_array($a_item_data) && count($a_item_data) > 0) {
                    $a_items[$key][$field_name] = strip_tags(stripslashes($a_item_data[0]['data_text']), "<br><a>");
                    if ($field_name == 'phone') {
                        $a_items[$key][$field_name] = $this->o_str->formatPhoneNumber(
                            $a_items[$key][$field_name],
                            $this->phone_format
                        );
                    }
                } else {
                    $a_items[$key][$field_name] = '';
                }
            }
        }
        return $a_items;
    }
    /**
     *  Creates a Twig Loader object.
     *  @param none
     *  @return object
    **/
    public function twigLoader()
    {
        $guide_path = APP_PATH . '/Guide/resources/templates/twig';
        $frame_path = APP_PATH . '/Framework/resources/templates/twig';
        $a_template_paths = array(
            $guide_path . '/default'  => 'default',
            $guide_path . '/pages'    => 'pages',
            $guide_path . '/elements' => 'elements',
            $guide_path . '/snippets' => 'snippets',
            $guide_path . '/manager'  => 'manager',
            $frame_path . '/main'     => 'fwmain',
            $frame_path . '/tests'    => 'fwtests'
        );
        $loader = new Twig_Loader_Filesystem($guide_path);
        foreach ($a_template_paths as $path => $namespace ) {
            $loader->prependPath($path, $namespace);
        }
        return $loader;
    }

    ### SETTERS ###
    /**
     *  Sets the value of $this->date_format.
     *  Verifies the date format is valid for php before
     *  setting it. If it isn't a valie format, doesn't set.
     *  @param str $value date format desired
     *  @return null
    **/
    public function setDateFormat($value = '')
    {
        if ($value == '') { return; }
        if (Date_Time::isValidDateFormat($value)) {
            $this->date_format = $value;
        }
    }
    /**
     *  Sets the value of $this->default_section.
     *  @param int $section_id
     *  @return null
    **/
    public function setDefaultSection($section_id = '')
    {
        $this->default_section = $section_id;
    }
    /**
     *  Sets the value of $num_to_display
     *  @param int $value defaults to 10
     *  @return null
    **/
    public function setNumToDisplay($value = 10)
    {
        $this->num_to_display = $value;
    }
    /**
     *  Sets the value of $phone_format
     *  Verifies value is valid formate else doesn't set it.
     *  @param str $value defaults to ''
     *  @return null
    **/
    public function setPhoneFormat($value = '')
    {
        switch ($value) {
            case 'XXX-XXX-XXXX':
            case '(XXX) XXX-XXXX':
            case 'XXX XXX XXXX':
            case 'XXX.XXX.XXXX':
            case 'AAA-BBB-CCCC':
            case '(AAA) BBB-CCCC':
            case 'AAA BBB CCCC':
            case 'AAA.BBB.CCCC':
                $this->phone_format = $value;
                return;
            default:
                return;
        }
    }

    ### GETTERS ###
    /**
     *  Gets the value of the date format.
     *  @param none
     *  @return str the value set
    **/
    public function getDateFormat()
    {
        return $this->date_format;
    }
    /**
     *  Gets the value of $this->default_section.
     *  @param none
     *  @return int $section_id
    **/
    public function getDefaultSection()
    {
        return $this->default_section;
    }
    /**
     *  Gets the value of $num_to_display
     *  @param none
     *  @return int the number of records to display
    **/
    public function getNumToDisplay()
    {
        return $this->num_to_display;
    }
    /**
     *  Gets the value of $phone_format.
     *  @param none
     *  @return str
    **/
    public function getPhoneFormat()
    {
        return $this->phone_format;
    }
}