<?php
/**
 *
 * JsScriptaculous Controls helper class.
 *
 * @category Solar
 *
 * @package Solar_View
 *
 * @author Clay Loveless <clay@killersoft.com>
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 * @version $Id$
 *
 */

/**
 * The parent JsScriptaculous class
 */
Solar::loadClass('Solar_View_Helper_JsScriptaculous');

/**
 *
 * @category Solar
 *
 * @package Solar_View
 *
 * @author Clay Loveless <clay@killersoft.com>
 *
 */
class Solar_View_Helper_JsScriptaculous_Control extends Solar_View_Helper_JsScriptaculous {

    /**
     *
     * Reference name for the type of JavaScript object this class produces
     *
     * @var string
     *
     */
    protected $_type = 'JsScriptaculous_Control';

    /**
     *
     * Constructor.
     *
     * @param array $config User-provided configuration values.
     *
     */
    public function __construct($config = null)
    {
        parent::__construct($config);
        $this->_needsFile('effects.js');
    }

    /**
     *
     * Method interface
     *
     * @return Solar_View_Helper_JsScriptaculous_Control
     *
     */
    public function control()
    {
        return $this;
    }

    /**
     *
     * Fetch method called by Solar_View_Helper_Js.
     *
     *
     */
    public function fetch($selector, $action)
    {
        $out = '';
        $json = Solar::factory('Solar_Json');

        switch ($action['name']) {

            case 'InPlaceEditor':

                // InPlaceEditor control extends Prototype's Ajax object
                $out .= "    \$\$('$selector').each(function(li){new Ajax.{$action['name']}(li";

                if (isset($action['url'])) {
                    $out .= ', ' . $json->encode($a['url']);
                }

                if (isset($action['options']) && !empty($action['options'])) {
                    $out .= ', ' . $json->encode($action['options']);
                }
                $out .= ")});\n";

                break;

            default:
                break;

        }

        return $out;
    }

    /** IN-PLACE EDITING CONTROLS **/

    /**
     *
     * In-place editing allows for AJAX-backed "on-the-fly" editing of
     * textfields. Attaching an in-place editor to a block of text will allow
     * it to be clicked on, which will convert the textfield to a input field
     * (if a single line of text) or a textarea field (if a multi-line block
     * of text).
     *
     * For $options, in-place editor controls support the following settings
     * [Ajax.InPlaceEditor][]
     *
     * : okButton       : (bool) If a submit button is shown in edit mode.
     *                    Defaults to true.
     * : okText         : (string) Text of submit button that submits the
     *                    changed value to the server. Defaults to "ok"
     * : cancelLink     : (bool) If a cancel link is shown in edit mode.
     *                    Defaults to true.
     * : savingText     : (string) Text shown while updated value is sent to
     *                    the server. Defaults to "Saving..."
     * : clickToEditText: (string) Text shown during mouseover of the editable
     *                    text. Defaults to "Click to edit"
     * : formId         : (string) The id given to the form element. Defaults
     *                    to the id of the element to edit plus 'InPlaceForm'
     * : externalControl: (string) ID of an element that acts as an external
     *                    control used to enter edit mode. The external control
     *                    will be hidden when entering edit mode, and shown
     *                    again when leaving edit mode. Defaults to null.
     * : rows           : (int) The row height of the input field. Any value
     *                    greater than 1 results in a multiline textarea for
     *                    input. Defaults to 1.
     * : onComplete     : (string) JavaScript code to run if update successful
     *                    with server. Defaults to
     *                    "function(transport, element) {new Effect.Highlight(element, {startcolor: this.options.highlightcolor});}"
     * : onFailure      : (string) JavaScript code to run if update failed with
     *                    server. Defaults to
     *                    "function(transport) {alert("Error communicating with the server: " + transport.responseText.stripTags());}"
     * : cols           : (int) The number of columns the text area should span.
     *                    Works for both single line and multi-line. No default
     *                    value.
     * : size           : (int) Synonym for 'cols' when using single-line (rows=1)
     *                    input. No default value.
     * : highlightcolor : (string) The highlight color on mouseover. Defaults
     *                    to value of Ajax.InPlaceEditor.defaultHighlightColor.
     * : highlightendcolor : (string) The color the highlight fades to. Defaults
     *                    to #FFFFFF.
     * : savingClassName: (string) CSS class added to the element while
     *                    displaying "Saving..." (removed when server responds)
     *                    Defaults to "inplaceeditor-saving"
     * : formClassName  : (string) CSS class used for the in place edit form.
     *                    Defaults to "inplaceeditor-form"
     * : LoadTextURL    : (string) Will cause the text for the edit box to be
     *                    loaded from the server. Useful if your text is
     *                    actually Wiki markup, Markdown, Textile, etc., and
     *                    formatted for display on the server. Defaults to null.
     * : loadingText    : (string) If the loadTextURL option is specified,
     *                    then this text is displayed while the text is being
     *                    loaded from the server. Defaults to "Loading..."
     * : callback       : (string) JavaScript function that will get executed
     *                    just before the request is sent to the server. Should
     *                    return parameters to be sent in the URL. Will get two
     *                    paramters, the entire form and the value of the
     *                    text control. Defaults to
     *                    "function(form) {Form.serialize(form)}"
     * : ajaxOptions    : (array) Options specified to all AJAX calls (loading
     *                    and saving text). These options are passed through to
     *                    the Prototype AJAX classes.
     *
     * The URL on the server-side gets the new value as the parameter "value"
     * via POST method, and should send the new value as the body of the response.
     * Server-side processing of markup formats like Markdown should be done if
     * necessary, with the output of that processing sent as the response.
     *
     * [Ajax.InPlaceEditor]: http://wiki.script.aculo.us/scriptaculous/show/Ajax.InPlaceEditor
     *
     * @param string $selector CSS selector of block to attach in-place editor
     * to
     *
     * @param string $url URL to submit the changed value to.  The server should
     * respond with the updated value.
     *
     * @param array $options Associative array of options for the in-place
     * editor control.
     *
     * @return object Solar_View_Helper_JsScriptaculous_Control
     *
     */
    public function inPlaceEditor($selector, $url, $options = array())
    {
        $this->_needsFile('controls.js');

        $details = array(
            'type'  => 'JsScriptaculous_Control',
            'name'  => 'InPlaceEditor',
            'url'   => $url,
            'options' => $options
        );
        $this->_view->js()->selectors[$selector][] = $details;

        return $this;
    }



    /** DRAG AND DROP CONTROLS **/

    /**
     *
     * Makes the element with the CSS selector specified by $selector draggable.
     *
     * @param string $selector CSS selector of element to make draggable
     *
     * @param array $options Assoc array of draggable element options
     *
     * @return Solar_View_Helper_JsScriptaculous
     *
     */
    public function draggable($selector, $options = array())
    {
        $this->_needsFile('effects.js');
        $this->_needsFile('dragdrop.js');

        $this->selectors[$selector][] = array('type' => 'draggable',
                                               'options' => $options);

        return $this;
    }

    /**
     * Makes the element with the CSS selector specified by $selector receive
     * dropped draggable elements (created by {@link draggable()}, and make
     * an Ajax call by default. The action called gets the DOM ID of the
     * dropped element as a parameter.
     *
     * @param string $selector CSS selector of element that should receive
     * dropped items
     *
     * @param array $options Assoc array of options for droppable item
     *
     * @return Solar_View_Helper_JsScriptaculous
     *
     */
    public function droppable($selector, $options = array())
    {
        $this->_needsFile('effects.js');
        $this->_needsFile('dragdrop.js');

        if (!isset($options['with'])) {
            $options['with'] = '\'id=\' + encodeURIComponent(el.id)';
        }

        if (!isset($options['ondrop'])) {
            $options['ondrop'] = 'function(el) {'
                . $this->remoteFunction($options) . '}';
        }

        // Clean out options
        $ajax_options = $this->ajax_options;
        foreach ($ajax_options as $key) {
            unset($options[$key]);
        }

        if (isset($options['accept'])) {
            $options['accept'] = $this->_arrayOrStringForJs($options['accept']);
        }

        if (isset($options['hoverclass'])) {
            $options['hoverclass'] = "'{$options['hoverclass']}'";
        }

        $this->selectors[$selector][] = array('type' => 'droppable',
                                               'options' => $options);

        return $this;
    }

    /**
     *
     * Makes the item with the CSS selector specified sortable by drag-and-drop,
     * and makes an Ajax call whenever the sort order has changed. By default,
     * the action called gets the serialized sortable element as parameters.
     *
     * @param string $selector CSS selector of element containing sortable items
     *
     * @param string $url URL to call via Ajax when sort order is changed
     *
     * @param array $options Assoc array of sortable controller options
     *
     * @return Solar_View_Helper_JsScriptaculous
     *
     * @todo Finish this method
     *
    public function sortable($selector, $url, $options = array())
    {
        return $this;
    }
     */



    /** AUTO-COMPLETION CONTROLS **/

    /**
     *
     * Autocompleting text input field (server powered)
     *
     * @param string $selector CSS selector of input field to attach completion
     * control to
     *
     * @param string $divToPopulate Div id to populate with auto-completion
     * choices
     *
     * @param string $url URL to query on server for auto-completion options
     *
     * @param array $options Assoc array of options for the auto-completion
     * control
     *
     * @return Solar_View_Helper_JsScriptaculous
     *
    public function autocompleter($selector, $divToPopulate, $url,
                                    $options = array())
    {
        $this->_needsFile('effects.js');
        $this->_needsFile('controls.js');
        return $this;
    }
     */

    /**
     *
     * Autocompleting text input field (local)
     *
     * @param string $selector CSS selector of input field to attach completion
     * control to
     *
     * @param string $divToPopulate Div id to populate with auto-completion
     * choices
     *
     * @param array $choices Array of choices to perform completion against
     *
     * @param array $options Assoc array of optionqs for the auto-completion
     * control
     *
     * @return Solar_View_Helper_JsScriptaculous
     *
    public function autocompleterLocal($selector, $divToPopulate,
                                    $choices = array(), $options = array())
    {
        $this->_needsFile('effects.js');
        $this->_needsFile('controls.js');
        return $this;
    }
     */




}
?>