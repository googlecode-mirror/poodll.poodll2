<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Defines the editing form for the poodllrecording question type.
 *
 * @package    qtype
 * @subpackage poodllrecording
 * @copyright  2012 Justin Hunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * PoodLL Recording question type editing form.
 *
 * @copyright  2012 PoodLL Recording Question 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_poodllrecording_edit_form extends question_edit_form {

    protected function definition_inner($mform) {
        $qtype = question_bank::get_qtype('poodllrecording');

        $mform->addElement('select', 'responseformat',
                get_string('responseformat', 'qtype_poodllrecording'), $qtype->response_formats());
        $mform->setDefault('responseformat', 'editor');

        $mform->addElement('editor', 'graderinfo', get_string('graderinfo', 'qtype_poodllrecording'),
                array('rows' => 10), $this->editoroptions);
    }

    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);
        if (empty($question->options)) {
            return $question;
        }
        $question->responseformat = $question->options->responseformat;
        $question->responsefieldlines = $question->options->responsefieldlines;
        $question->attachments = $question->options->attachments;

        $draftid = file_get_submitted_draft_itemid('graderinfo');
        $question->graderinfo = array();
        $question->graderinfo['text'] = file_prepare_draft_area(
            $draftid,           // draftid
            $this->context->id, // context
            'qtype_poodllrecording',      // component
            'graderinfo',       // filarea
            !empty($question->id) ? (int) $question->id : null, // itemid
            $this->fileoptions, // options
            $question->options->graderinfo // text
        );
        $question->graderinfo['format'] = $question->options->graderinfoformat;
        $question->graderinfo['itemid'] = $draftid;

        return $question;
    }


    public function qtype() {
        return 'poodllrecording';
    }
}
