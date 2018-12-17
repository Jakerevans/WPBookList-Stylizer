<?php
/**
 * WPBookList WPBookList_Stylizer_Form Submenu Class
 *
 * @author   Jake Evans
 * @category ??????
 * @package  ??????
 * @version  1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WPBookList_Stylizer_Form', false ) ) :
/**
 * WPBookList_Stylizer_Form Class.
 */
class WPBookList_Stylizer_Form {

  public function output_stylizer_form(){
    global $wpdb;

    $kindle_active = false;
    $storefront_active = false;
    $stringbegin = '';
    $string1 = '';
    $string2 = '';
    $string3 = '';
    $string4 = '';
    $string5 = '';
    $string6 = '';
    $string7 = '';
    $string8 = '';
    $string9 = '';
    $string10 = '';
    $string11 = '';
    $string12 = '';
    $string13 = '';
    $string14 = '';
    $string15 = '';
    $string16 = '';
    $string17 = '';
    $string18 = '';
    $string19 = '';
    $string20 = '';
    $string21 = '';
    $string22 = '';
    $string23 = '';
    $string24 = '';
    $string25 = '';
    $string26 = '';
    $string27 = '';
    $string28 = '';
    $string29 = '';


    $active_plugins = get_option('active_plugins');
    foreach ($active_plugins as $key => $value) {
      if($value == 'wpbooklist-kindlepreview/wpbooklist-kindlepreview.php'){
        $kindle_active = true;
      }

      if($value == 'wpbooklist-storefront/wpbooklist-storefront.php'){
        $storefront_active = true;

        global $wpdb;
        $table = $wpdb->prefix.'wpbooklist_jre_storefront_options';
        $storefront_options = $wpdb->get_row("SELECT * FROM $table");
      }
    }

    $stringbegin = '<p id="wpbooklist-stylizer-p">Here you can modify the default styling of your <span class="wpbooklist-color-orange-italic">WPBookList</span> plugin. Simply select a view, click on which orange element you\'d like to modify below, make your changes, and click the <span class="wpbooklist-color-orange-italic">\'Save Styling\'</span> button!</p><div class="wpbooklist-spinner" id="wpbooklist-spinner-stylizer-1"></div><select id="wpbooklist-stylizer-select"><option selected default disabled>Select a View to Style...</option><option>Book View</option><option>Library View</option></select><iframe class="wpbooklist-stylizer-interact" id="wpbooklist-stylizer-iframe"></iframe><div class="wpbooklist-stylizer-interact" id="wpbooklist-stylizer-styles-div"><div id="wpbooklist-stylizer-bookview-styles">

      ';

    $string1 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-1">
        <p class="wpbooklist-stylizer-title">Main Title</p>
        <div data-element="maintitle" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string2 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-2">
        <p class="wpbooklist-stylizer-title">Share Title</p>
        <div data-element="sharetitle" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string3 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-3">
        <p class="wpbooklist-stylizer-title">Purchase Title</p>
        <div data-element="purchasetitle" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string4 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-4">
        <p class="wpbooklist-stylizer-title">Similar Title</p>
        <div data-element="similartitle" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    if($kindle_active == true){
      $string5 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-5">
        <p class="wpbooklist-stylizer-title">Kindle Title</p>
        <div data-element="kindletitle" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';
    }

    $string5 = $string5.'<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-6">
        <p class="wpbooklist-stylizer-title">Description Title</p>
        <div data-element="descriptiontitle" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string6 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-7">
        <p class="wpbooklist-stylizer-title">Amazon Review Title</p>
        <div data-element="amazonreviewtitle" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string7 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-8">
        <p class="wpbooklist-stylizer-title">Notes Title</p>
        <div data-element="notestitle" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string8 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-9">
        <p class="wpbooklist-stylizer-title">Purchase Images</p>
        <div data-element="purchaseimages" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string9 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-10">
        <p class="wpbooklist-stylizer-title">Cover Image</p>
        <div data-element="coverimage" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string10 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-11">
        <p class="wpbooklist-stylizer-title">Similar Cover Images</p>
        <div data-element="similarcoverimage" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';
/*
    $string11 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-12">
        <p class="wpbooklist-stylizer-title">Sharing Images</p>
        <div data-element="sharingimage" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';
*/
    $string12 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-13">
        <p class="wpbooklist-stylizer-title">Book Details</p>
        <div data-element="bookdetails" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string13 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-14">
        <p class="wpbooklist-stylizer-title">Book Detail Labels</p>
        <div data-element="detaillabels" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string14 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-15">
        <p class="wpbooklist-stylizer-title">Book Page & Post Links</p>
        <div data-element="bookpagelink" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    if($storefront_active == true){
      $string15 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-16">
        <p class="wpbooklist-stylizer-title">StoreFront Purchase Link</p>
        <div data-element="storefrontpurchase" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';
    }

    // Beginning of the Style Blocks for the Library View stuff
    $string16 = '</div><div id="wpbooklist-stylizer-libraryview-styles"><div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-17">
        <p class="wpbooklist-stylizer-title">Sort by...</p>
        <div data-element="sortby" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string17 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-18">
        <p class="wpbooklist-stylizer-title">Search Text</p>
        <div data-element="searchtext" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string18 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-19">
        <p class="wpbooklist-stylizer-title">Search Box</p>
        <div data-element="searchbox" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string19 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-20">
        <p class="wpbooklist-stylizer-title">Search Button</p>
        <div data-element="searchbutton" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string20 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-21">
        <p class="wpbooklist-stylizer-title">Stats Area</p>
        <div data-element="statsdiv" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string21 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-22">
        <p class="wpbooklist-stylizer-title">Quote</p>
        <div data-element="quote" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string22 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-23">
        <p class="wpbooklist-stylizer-title">Quote Attribution</p>
        <div data-element="quoteattribution" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string23 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-24">
        <p class="wpbooklist-stylizer-title">Outer Book Container</p>
        <div data-element="outerbookcontainer" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string24 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-25">
        <p class="wpbooklist-stylizer-title">Inner Book Container</p>
        <div data-element="innerbookcontainer" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string25 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-26">
        <p class="wpbooklist-stylizer-title">Cover Image</p>
        <div data-element="libcoverimage" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    $string26 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-27">
        <p class="wpbooklist-stylizer-title">Book Title</p>
        <div data-element="booktitle" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

    if($storefront_active == true){
      $string27 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-28">
        <p class="wpbooklist-stylizer-title">StoreFront Price</p>
        <div data-element="storefrontpurchasetext" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';

      if($storefront_options->libraryimg == 'Purchase Now!'){
        $string28 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-29">
        <p class="wpbooklist-stylizer-title">StoreFront Purchase Text</p>
        <div data-element="storefrontpurchaseimgtext" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';
      } else {
        $string29 = '<div class="wpbooklist-stylizer-section-container" id="wpbooklist-stylizer-section-30">
        <p class="wpbooklist-stylizer-title">StoreFront Purchase Image</p>
        <div data-element="storefrontpurchaseimage" class="wpbooklist-stylizer-styles-holder">'.$this->styleparts().'</div></div>';
      }

    }
        
    $stringend =  '</div><div id="wpbooklist-stylizer-save-reset-div"><button id="wpbooklist-stylizer-save-styling-button">Save Styling</button><button id="wpbooklist-stylizer-clear-styling-button">Reset All Styling</button><div id="wpbooklist-stylizer-success-div"></div><div class="wpbooklist-spinner" id="wpbooklist-spinner-stylizer-2"></div></div>
    </div>';

    echo $stringbegin.$string1.$string2.$string3.$string4.$string5.$string6.$string7.$string8.$string9.$string10.$string11.$string12.$string13.$string14.$string15.$string16.$string17.$string18.$string19.$string20.$string21.$string22.$string23.$string24.$string25.$string26.$string27.$string28.$string29.$stringend;
  }

  public function styleparts(){

    $intro  = '<div id="wpbooklist-stylizer-styles-intro">Set Your Styles Below:</div>';

    $d1 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-1">
      <label>Height:</label>
      <input class="wpbooklist-stylizer-interact" id="wpbooklist-stylizer-d1" type="number" />
    </div>';

    $d2 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-2">
      <label>Width:</label>
      <input class="wpbooklist-stylizer-interact" id="wpbooklist-stylizer-d2" type="number" />
    </div>';

    $f1 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-3">
      <label>Font Size:</label>
      <input value="22" placeholder="22" class="wpbooklist-stylizer-interact" id="wpbooklist-stylizer-f1" type="number" />
    </div>';

    $f2 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-4">
      <label>Font Color:</label>
      <input class="wpbooklist-stylizer-interact jscolor" id="wpbooklist-stylizer-f2" type="text" />
    </div>';

    $f3 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-5">
      <label>Font Family:</label>
      <select class="wpbooklist-stylizer-interact wpbooklist-stylizer-interact-family-select" id="wpbooklist-stylizer-f3"></select>
    </div>';

    $f4 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-6">
      <label>Font Style:</label>
      <select class="wpbooklist-stylizer-interact wpbooklist-stylizer-interact-style-select" id="wpbooklist-stylizer-f4">
        <option selected disabled default>Select a Font Style...</option>
        <option>Normal</option>
        <option>Italic</option>
      </select>
    </div>';

    $f5 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-6">
      <label>Font Weight:</label>
      <select class="wpbooklist-stylizer-interact wpbooklist-stylizer-interact-weight-select" id="wpbooklist-stylizer-f5">
        <option selected disabled default>Select a Font Weight...</option>
        <option>100</option>
        <option>200</option>
        <option>300</option>
        <option>400</option>
        <option>500</option>
        <option>600</option>
        <option>700</option>
        <option>800</option>
        <option>900</option>
        <option>Bold</option>
        <option>Bolder</option>
        <option>Lighter</option>
        <option>Normal</option>
      </select>
    </div>';

    $f6 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-7">
      <label>Font Variant:</label>
      <select class="wpbooklist-stylizer-interact wpbooklist-stylizer-interact-variant-select" id="wpbooklist-stylizer-f6" type="number">
        <option selected disabled default>Select a Font Variant...</option>
        <option>Small-Caps</option>
        <option>Unicase</option>
      </select>
    </div>';

    $f7 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-8">
      <label>Line Height:</label>
      <input class="wpbooklist-stylizer-interact" id="wpbooklist-stylizer-f7" type="number" />
    </div>';

    $f8 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-9">
      <label>Letter Spacing:</label>
      <input class="wpbooklist-stylizer-interact" id="wpbooklist-stylizer-f8" type="number" />
    </div>';

    $f9 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-10">
      <label>Stroke Width:</label>
      <input class="wpbooklist-stylizer-interact" id="wpbooklist-stylizer-f9" type="number" />
    </div>';

    $f10 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-11">
      <label>Stroke Color:</label>
      <input class="wpbooklist-stylizer-interact jscolor" id="wpbooklist-stylizer-f10" type="text" />
    </div>';

    $f12 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-13">
      <label>Background Color:</label>
      <input class="wpbooklist-stylizer-interact jscolor" id="wpbooklist-stylizer-f12" type="text" />
    </div>';

    $f11 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-12">
      <label>Text Align:</label>
      <select class="wpbooklist-stylizer-interact wpbooklist-stylizer-interact-align-select" id="wpbooklist-stylizer-f11">
        <option selected disabled default>Select a Text Alignment...</option>
        <option>Left</option>
        <option>Right</option>
        <option>Center</option>
        <option>Justify</option>
        <option>End</option>
        <option>Start</option>
      </select>
    </div>';

    $p1 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-13">
      <label>Margin:</label>
      <input class="wpbooklist-stylizer-short-num wpbooklist-stylizer-interact" id="wpbooklist-stylizer-p1-1" type="number" />
      <input class="wpbooklist-stylizer-short-num wpbooklist-stylizer-interact" id="wpbooklist-stylizer-p1-2" type="number" />
      <input class="wpbooklist-stylizer-short-num wpbooklist-stylizer-interact" id="wpbooklist-stylizer-p1-3" type="number" />
      <input class="wpbooklist-stylizer-short-num wpbooklist-stylizer-interact" id="wpbooklist-stylizer-p1-4" type="number" />
    </div>';

    $p2 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-14">
      <label>Padding:</label>
      <input class="wpbooklist-stylizer-short-num wpbooklist-stylizer-interact" id="wpbooklist-stylizer-p2-1" type="number" />
      <input class="wpbooklist-stylizer-short-num wpbooklist-stylizer-interact" id="wpbooklist-stylizer-p2-2" type="number" />
      <input class="wpbooklist-stylizer-short-num wpbooklist-stylizer-interact" id="wpbooklist-stylizer-p2-3" type="number" />
      <input class="wpbooklist-stylizer-short-num wpbooklist-stylizer-interact" id="wpbooklist-stylizer-p2-4" type="number" />
    </div>';

    $b1 = '<div class="wpbooklist-stylizer-style-block" id="wpbooklist-stylizer-style-block-15">
      <label>Box-Shadow:</label>
      <select class="wpbooklist-stylizer-interact" id="wpbooklist-stylizer-shadow-inset"><option>Normal</option><option>Inset</option></select>
      <input class="wpbooklist-stylizer-short-num wpbooklist-stylizer-interact" id="wpbooklist-stylizer-b1-1" type="number" />
      <input class="wpbooklist-stylizer-short-num wpbooklist-stylizer-interact" id="wpbooklist-stylizer-b1-2" type="number" />
      <input class="wpbooklist-stylizer-short-num wpbooklist-stylizer-interact" id="wpbooklist-stylizer-b1-3" type="number" />
      <input class="wpbooklist-stylizer-short-num wpbooklist-stylizer-interact" id="wpbooklist-stylizer-b1-4" type="number" />
      <input class="wpbooklist-stylizer-interact jscolor" id="wpbooklist-stylizer-b1" type="text" />
    </div>';




    return $intro.$d1.$d2.$f1.$f2.$f3.$f4.$f5.$f6.$f7.$f8.$f9.$f10.$f12.$f11.$p1.$p2.$b1;
  }
}

endif;