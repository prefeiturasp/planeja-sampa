<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomForms main form html template
 *
 * 	@see EasyContactFormsCustomForms::getMainForm()
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */


EasyContactFormsLayout::getFormHeader('ufo-formpage ufo-mainform ufo-' . strtolower($obj->type));
echo EasyContactFormsUtils::getTypeFormDescription($obj->getId(), 'CustomForms');
EasyContactFormsLayout::getFormHeader2Body();

?>
  <div>
    <?php
    EasyContactFormsLayout::getTabHeader(
      array(
        'GeneralSettings',
        'CustomFormFields',
        'Confirmation',
        'Notification',
        'Appearance',
        'CustomFormsEntries',
      ),
    'top', '1')
    ?>
    <input type='hidden' id='switchhandler' value='AppMan.addTabSwitchHandler(function(tab){var id = tab.attr("id"); var names = AppMan.Utils.idSplit(id); var divid=AppMan.Utils.idJoin(names[0],"buttons");$b=jQuery("#"+divid);var tName=names[1];if (tName =="CustomFormFields1"){$b.hide();}else{$b.show();}}, ["GeneralSettings1", "CustomFormFields1", "Confirmation1", "Notification1", "Appearance1", "CustomFormsEntries1"])' class='ufo-id-link ufo-eval'/>
    <div class='ufo-tab-wrapper ufo-tab-top'>
      <div id='GeneralSettings1' class='ufo-tabs ufo-tab1 ufo-active'>
        <div>
          <div class='ufo-float-left ufo-width50'>
            <label for='Description'>
              <?php echo EasyContactFormsT::get('FormTitle');?>
              <span class='mandatoryast'>*</span>
              <span id='DescriptionHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
            </label>
            <input type='hidden' id='DescriptionHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_FormTitle');?>' class='ufo-id-link'/>
            <input type='text' id='Description' value='<?php echo $obj->get('Description');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "Description";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.required={};c.required.msg=AppMan.resources.ThisFieldIsRequired;c.events.blur.push("required");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='Description-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
          <div class='ufo-float-right ufo-width50'>
            <label for='ShortCode'>
              <?php echo EasyContactFormsT::get('ShortCode');?>
              <span id='ShortCodeHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
            </label>
            <input type='hidden' id='ShortCodeHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_ShortCode');?>' class='ufo-id-link'/>
            <input type='text' id='ShortCode' value='<?php echo $obj->get('ShortCode');?>' READONLY class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "ShortCode";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("300");c.max="300";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='ShortCode-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
          <div style='clear:left'></div>
        </div>
        <div></div>
        <div>
          <fieldset>
            <legend>
              <?php echo EasyContactFormsT::get('SubmissionActions');?>
            </legend>
            <div class='ufo-float-left ufo-width50'>
              <div>
                 <label for='ShowSubmissionSuccess'>
                   <input type='checkbox' id='ShowSubmissionSuccess' value='<?php echo $obj->ShowSubmissionSuccess;?>' <?php echo $obj->ShowSubmissionSuccessChecked;?> class='ufo-cb checkbox ufo-formvalue' style='margin-top:0;margin-bottom:0' onchange='this.value=(this.checked)?"on":"off";ufo.cbDisable(this, false, ["SubmissionSuccessText"]);'/>
                   <?php echo EasyContactFormsT::get('ShowSubmissionSuccess');?>
                   <span id='ShowSubmissionSuccessHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                 </label>
                 <input type='hidden' id='ShowSubmissionSuccessHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_ShowSubmissionSuccess');?>' class='ufo-id-link'/>
              </div>
              <div>
                 <label for='SubmissionSuccessText' class='ufo-label-top'><?php echo EasyContactFormsT::get('SubmissionSuccessText');?></label>
                 <textarea id='SubmissionSuccessText' class='textinput ufo-textarea ufo-formvalue' style='width:100%;height:330px' <?php echo $obj->SubmissionSuccessTextDisabled;?>><?php echo $obj->get('SubmissionSuccessText');?></textarea>
              </div>
            </div>
            <div class='ufo-float-right ufo-width50'>
              <div>
                 <label for='Redirect'>
                   <input type='checkbox' id='Redirect' value='<?php echo $obj->Redirect;?>' <?php echo $obj->RedirectChecked;?> class='ufo-cb checkbox ufo-formvalue' style='margin-top:0;margin-bottom:0' onchange='this.value=(this.checked)?"on":"off";ufo.cbDisable(this, false, ["RedirectURL"]);'/>
                   <?php echo EasyContactFormsT::get('Redirect');?>
                   <span id='RedirectHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                 </label>
                 <input type='hidden' id='RedirectHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_Redirect');?>' class='ufo-id-link'/>
                 <label for='RedirectURL'><?php echo EasyContactFormsT::get('RedirectURL');?></label>
                 <input type='text' id='RedirectURL' value='<?php echo $obj->get('RedirectURL');?>' class='textinput ufo-text ufo-formvalue' style='width:100%' <?php echo $obj->RedirectURLDisabled;?>/>
              </div>
              <div>
                 <fieldset>
                   <legend>
                     <?php echo EasyContactFormsT::get('FormStatistics');?>
                   </legend>
                   <label for='IncludeIntoReporting'>
                     <input type='checkbox' id='IncludeIntoReporting' value='<?php echo $obj->IncludeIntoReporting;?>' <?php echo $obj->IncludeIntoReportingChecked;?> class='ufo-cb checkbox ufo-formvalue' style='margin-top:0;margin-bottom:0' onchange='this.value=(this.checked)?"on":"off";'/>
                     <?php echo EasyContactFormsT::get('ShowOnDashboard');?>
                   </label>
                   <div>
                     <label for='Impressions'>
                       <?php echo EasyContactFormsT::get('Impressions');?>
                       <span id='ImpressionsHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                     </label>
                     <input type='hidden' id='ImpressionsHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_Impressions');?>' class='ufo-id-link'/>
                     <?php echo $obj->get('Impressions');?>
                   </div>
                   <div>
                     <label for='TotalEntries'>
                       <?php echo EasyContactFormsT::get('TotalEntries');?>
                       <span id='TotalEntriesHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                     </label>
                     <input type='hidden' id='TotalEntriesHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_TotalEntries');?>' class='ufo-id-link'/>
                     <?php echo $obj->get('TotalEntries');?>
                   </div>
                   <div>
                     <label for='TotalProcessedEntries'>
                       <?php echo EasyContactFormsT::get('TotalProcessedEntries');?>
                       <span id='TotalProcessedEntriesHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                     </label>
                     <input type='hidden' id='TotalProcessedEntriesHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_TotalProcessedEntries');?>' class='ufo-id-link'/>
                     <?php echo $obj->get('TotalProcessedEntries');?>
                   </div>
                   <?php echo EasyContactFormsIHTML::getButton(
                     array(
                       'label' => EasyContactFormsT::get('ResetStatistics'),
                       'events' => " onclick='ufoCf.resetStatistics(this, $obj->jsconfig)'",
                       'iclass' => " class='icon_reset' ",
                       'bclass' => "button internalimage",
                     )
                   );?>
                 </fieldset>
              </div>
            </div>
            <div style='clear:left'></div>
          </fieldset>
        </div>
      </div>
      <div id='CustomFormFields1' class='ufo-tabs ufo-tab1 ufo-customform-designer-div'>
        <div style='vertical-align:top;overflow:hidden'>
          <input type='hidden' value='AppMan.initRedirect("CustomFormFields1", {specialfilter:"[{\"property\":\"CustomForms\", \"value\":{\"values\":[<?php echo $obj->get('id');?>]}}]", viewTarget:"CustomFormFieldsDiv", t:"CustomFormFields", m:"viewDetailed"}, [{property:"CustomForms", value:{values:[<?php echo $obj->get('id');?>]}}])' class='ufo-eval'/>
          <div id='CustomFormFieldsDiv' class='innerview' style='vertical-align:top;overflow:hidden'></div>
        </div>
      </div>
      <div id='Confirmation1' class='ufo-tabs ufo-tab1'>
        <div class='ufo-float-left'>
          <div style='padding:0 5px'>
            <div>
              <label for='SendConfirmation'>
                 <?php echo EasyContactFormsT::get('SendConfirmation');?>
                 <span id='SendConfirmationHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
              </label>
              <input type='hidden' id='SendConfirmationHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_SendConfirmation');?>' class='ufo-id-link'/>
              <input type='checkbox' id='SendConfirmation' value='<?php echo $obj->SendConfirmation;?>' <?php echo $obj->SendConfirmationChecked;?> class='ufo-cb checkbox ufo-formvalue' onchange='this.value=(this.checked)?"on":"off";'/>
            </div>
            <div>
              <label for='ConfirmationSubject'>
                 <?php echo EasyContactFormsT::get('ConfirmationSubject');?>
                 <span id='ConfirmationSubjectHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
              </label>
              <input type='hidden' id='ConfirmationSubjectHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_ConfirmationSubject');?>' class='ufo-id-link'/>
              <input type='text' id='ConfirmationSubject' value='<?php echo $obj->get('ConfirmationSubject');?>' class='textinput ufo-text ufo-formvalue' style='width:250px'/>
              <input type='hidden' value='var c = {};c.id = "ConfirmationSubject";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
              <div id='ConfirmationSubject-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
            </div>
          </div>
        </div>
        <div class='ufo-float-left'>
          <div style='padding:0 5px'>
            <div>
              <label for='SendFrom'>
                 <?php echo EasyContactFormsT::get('SendFrom');?>
                 <span id='SendFromHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
              </label>
              <input type='hidden' id='SendFromHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_SendFrom');?>' class='ufo-id-link'/>
              <input type='text' id='SendFrom' value='<?php echo $obj->get('SendFrom');?>' class='textinput ufo-text ufo-formvalue' style='width:250px'/>
              <input type='hidden' value='var c = {};c.id = "SendFrom";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
              <div id='SendFrom-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
            </div>
            <div>
              <label for='ConfirmationReplyToAddress'>
                 <?php echo EasyContactFormsT::get('ConfirmationReplyToAddress');?>
                 <span id='ConfirmationReplyToAddressHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
              </label>
              <input type='hidden' id='ConfirmationReplyToAddressHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_ConfirmationReplyToAddress');?>' class='ufo-id-link'/>
              <input type='text' id='ConfirmationReplyToAddress' value='<?php echo $obj->get('ConfirmationReplyToAddress');?>' class='textinput ufo-text ufo-formvalue' style='width:250px'/>
              <input type='hidden' value='var c = {};c.id = "ConfirmationReplyToAddress";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
              <div id='ConfirmationReplyToAddress-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
            </div>
          </div>
        </div>
        <div class='ufo-float-left'>
          <div style='position:relative;padding:0 5px'>
            <div>
              <div id='EmailAdvanced' class='ufo-id-link' style='display:none;padding:0 5px'>
                 <div>
                   <label for='SendFromAddress'>
                     <?php echo EasyContactFormsT::get('SendFromAddress');?>
                     <span id='SendFromAddressHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                   </label>
                   <input type='hidden' id='SendFromAddressHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_SendFromAddress');?>' class='ufo-id-link'/>
                   <input type='text' id='SendFromAddress' value='<?php echo $obj->get('SendFromAddress');?>' class='textinput ufo-text ufo-formvalue' style='width:250px'/>
                   <input type='hidden' value='var c = {};c.id = "SendFromAddress";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
                   <div id='SendFromAddress-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
                 </div>
                 <div>
                   <label for='ConfirmationReplyToName'>
                     <?php echo EasyContactFormsT::get('ConfirmationReplyToName');?>
                     <span id='ConfirmationReplyToNameHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                   </label>
                   <input type='hidden' id='ConfirmationReplyToNameHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_ConfirmationReplyToName');?>' class='ufo-id-link'/>
                   <input type='text' id='ConfirmationReplyToName' value='<?php echo $obj->get('ConfirmationReplyToName');?>' class='textinput ufo-text ufo-formvalue' style='width:250px'/>
                   <input type='hidden' value='var c = {};c.id = "ConfirmationReplyToName";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
                   <div id='ConfirmationReplyToName-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
                 </div>
              </div>
            </div>
            <div>
              <div class='ufo-id-link' style='position:absolute;padding:25px;' onclick='var eaid = AppMan.Utils.idJoin(AppMan.hash, "EmailAdvanced");jQuery("#"+eaid).fadeIn();jQuery(this).hide();;'><a>Advanced</a></div>
            </div>
          </div>
        </div>
        <div style='clear:left'></div>
        <div style='margin-top:10px'>
          <div style='float:right;height:290px;padding-top:20px;overflow:auto;width:130px'>
            <?php $obj->getEmailTemplateLinks('ConfirmationText');?>
          </div>
          <div style='margin-right:130px;overflow:auto;padding-right:15px'>
            <label for='ConfirmationText' class='ufo-label-top'>
              <?php echo EasyContactFormsT::get('ConfirmationText');?>
              <span id='ConfirmationTextHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
            </label>
            <input type='hidden' id='ConfirmationTextHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_ConfirmationText');?>' class='ufo-id-link'/>
            <?php if (EasyContactFormsApplicationSettings::getInstance()->get('UseTinyMCE')) : 
              EasyContactFormsIHTML::getTinyMCE('ConfirmationText');
            endif; ?>
            <textarea id='ConfirmationText' class='textinput ufo-formvalue' style='width:100%;height:330px'><?php echo $obj->get('ConfirmationText');?></textarea>
          </div>
        </div>
      </div>
      <div id='Notification1' class='ufo-tabs ufo-tab1'>
        <?php EasyContactFormsLayout::getTabHeader(array('Settings', 'CustomFormMailingList'), 'top', '2');?>
        <div class='ufo-tab-wrapper ufo-tab-top'>
          <div id='Settings2' class='ufo-tabs ufo-tab2 ufo-active'>
            <div style='padding:5px 15px'>
              <div class='ufo-float-left ufo-width50'>
                 <div>
                   <label for='NotificationSubject'>
                     <?php echo EasyContactFormsT::get('NotificationSubject');?>
                     <span id='NotificationSubjectHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                   </label>
                   <input type='hidden' id='NotificationSubjectHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_NotificationSubject');?>' class='ufo-id-link'/>
                   <input type='text' id='NotificationSubject' value='<?php echo $obj->get('NotificationSubject');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
                   <input type='hidden' value='var c = {};c.id = "NotificationSubject";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
                   <div id='NotificationSubject-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
                 </div>
                 <div>
                   <label for='ObjectOwner'>
                     <?php echo EasyContactFormsT::get('PrimaryReceiver');?>
                     <span id='ObjectOwnerHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                   </label>
                   <input type='hidden' id='ObjectOwnerHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_PrimaryReceiver');?>' class='ufo-id-link'/>
                   <?php EasyContactFormsIHTML::getAS($obj->ObjectOwner);?>
                 </div>
              </div>
              <div class='ufo-float-right ufo-width50'>
                 <div>
                   <label for='IncludeVisitorsAddressInReplyTo'>
                     <?php echo EasyContactFormsT::get('IncludeVisitorsAddressInReplyTo');?>
                     <span id='IncludeVisitorsAddressInReplyToHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                   </label>
                   <input type='hidden' id='IncludeVisitorsAddressInReplyToHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_IncludeVisitorsAddressInReplyTo');?>' class='ufo-id-link'/>
                   <input type='checkbox' id='IncludeVisitorsAddressInReplyTo' value='<?php echo $obj->IncludeVisitorsAddressInReplyTo;?>' <?php echo $obj->IncludeVisitorsAddressInReplyToChecked;?> class='ufo-cb checkbox ufo-formvalue' onchange='this.value=(this.checked)?"on":"off";'/>
                 </div>
                 <div>
                   <label for='ReplyToNameTemplate'>
                     <?php echo EasyContactFormsT::get('ReplyToNameTemplate');?>
                     <span id='ReplyToNameTemplateHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                   </label>
                   <input type='hidden' id='ReplyToNameTemplateHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_ReplyToNameTemplate');?>' class='ufo-id-link'/>
                   <input type='text' id='ReplyToNameTemplate' value='<?php echo $obj->get('ReplyToNameTemplate');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
                   <input type='hidden' value='var c = {};c.id = "ReplyToNameTemplate";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
                   <div id='ReplyToNameTemplate-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
                 </div>
              </div>
              <div style='clear:left'></div>
              <div style='float:right;height:220px;padding-top:20px;overflow:auto;width:130px'>
                 <?php $obj->getEmailTemplateLinks('NotificationText');?>
              </div>
              <div style='margin-right:130px;overflow:auto;padding-right:15px'>
                 <label for='NotificationText' class='ufo-label-top'>
                   <?php echo EasyContactFormsT::get('NotificationText');?>
                   <span id='NotificationTextHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                 </label>
                 <input type='hidden' id='NotificationTextHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_NotificationText');?>' class='ufo-id-link'/>
                 <?php if (EasyContactFormsApplicationSettings::getInstance()->get('UseTinyMCE')) : 
                   EasyContactFormsIHTML::getTinyMCE('NotificationText');
                 endif; ?>
                 <textarea id='NotificationText' class='ufo-formvalue' style='width:100%;height:260px'><?php echo $obj->get('NotificationText');?></textarea>
              </div>
            </div>
          </div>
          <div id='CustomFormMailingList2' class='ufo-tabs ufo-tab2'>
            <input type='hidden' value='AppMan.initRedirect("CustomFormMailingList2", {viewTarget:"UsersDiv", t:"Users", m:"mtmview", n:"manage", a:"{\"m\":\"mtmview\", \"ca\":[{\"mt\":\"CustomForms_MailingLists\", \"oid\":\"<?php echo $obj->get('id');?>\", \"fld\":\"CustomForms\", \"t\":\"CustomForms\", \"n\":\"Contacts\"}]}"})' class='ufo-eval'/>
            <div id='UsersDiv' class='mtmview innerview' style='width:270px;float:right'></div>
            <input type='hidden' value='AppMan.initRedirect("CustomFormMailingList2", {specialfilter:"[{\"property\":\"CustomForms\", \"value\":{\"values\":[<?php echo $obj->get('id');?>]}}]", viewTarget:"CustomForms_MailingListsDiv", t:"CustomForms_MailingLists", m:"mtmview", n:"CustomForms"}, [{property:"CustomForms", value:{values:[<?php echo $obj->get('id');?>]}}])' class='ufo-eval'/>
            <div id='CustomForms_MailingListsDiv' class='mtmview innerview' style='margin-right:275px'></div>
            <div style='clear:both;height:1px'></div>
          </div>
        </div>
      </div>
      <div id='Appearance1' class='ufo-tabs ufo-tab1'>
        <?php
        EasyContactFormsLayout::getTabHeader(
          array(
            'Settings',
            'StyleSheet',
            'ConfirmationStyleSheet',
            'NotificationStyleSheet',
          ),
        'top', '3')
        ?>
        <div class='ufo-tab-wrapper ufo-tab-top'>
          <div id='Settings3' class='ufo-tabs ufo-tab3 ufo-active'>
            <div class='ufo-float-left ufo-width50'>
              <div style='padding:5px 10px'>
                 <div class='ufo-float-left ufo-width50'>
                   <label><?php echo EasyContactFormsT::get('FormWidth');?></label>
                   <div style='position:relative;padding-right:50px'>
                     <input type='string' id='Width' value='<?php echo $obj->get('Width');?>' class='textinput textinput ufo-text ufo-formvalue' style='width:100%'/>
                     <input type='hidden' value='var c = {};c.id = "Width";c.events = {};c.events.blur = [];c.integer={};c.integer.msg=AppMan.resources.ThisIsAnIntegerField;c.events.blur.push("integer");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
                     <div id='Width-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
                     <select id='WidthUnit' class='textinput ufo-formvalue ufo-select inputselect' style='right:0;position:absolute;top:0;width:49px'>
                       <?php echo $obj->getListHTML(array( (object) array('id'=>'px', 'Description'=>'px'), (object) array('id'=>'em', 'Description'=>'em'), (object) array('id'=>'%', 'Description'=>'%')), 'WidthUnit', TRUE);?>
                     </select>
                   </div>
                   <div style='clear:left'></div>
                 </div>
                 <div class='ufo-float-right ufo-width50'>
                   <label for='LineHeight'>
                     <?php echo EasyContactFormsT::get('LineMarginHeight');?>
                     <span id='LineHeightHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                   </label>
                   <input type='hidden' id='LineHeightHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_LineMarginHeight');?>' class='ufo-id-link'/>
                   <div style='position:relative;padding-right:50px'>
                     <input type='string' id='LineHeight' value='<?php echo $obj->get('LineHeight');?>' class='textinput textinput ufo-text ufo-formvalue' style='width:100%'/>
                     <input type='hidden' value='var c = {};c.id = "LineHeight";c.events = {};c.events.blur = [];c.integer={};c.integer.msg=AppMan.resources.ThisIsAnIntegerField;c.events.blur.push("integer");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
                     <div id='LineHeight-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
                     <select id='LineHeightUnit' class='textinput ufo-formvalue ufo-select inputselect' style='right:0;position:absolute;top:0;width:49px'>
                       <?php echo $obj->getListHTML(array( (object) array('id'=>'px', 'Description'=>'px'), (object) array('id'=>'em', 'Description'=>'em'), (object) array('id'=>'%', 'Description'=>'%')), 'LineHeightUnit', TRUE);?>
                     </select>
                   </div>
                   <div style='clear:left'></div>
                 </div>
                 <div style='clear:left'></div>
                 <label for='Style'>
                   <?php echo EasyContactFormsT::get('Style');?>
                   <span id='StyleHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                 </label>
                 <input type='hidden' id='StyleHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_Style');?>' class='ufo-id-link'/>
                 <select id='Style' class='textinput ufo-formvalue ufo-select inputselect' style='width:100%'>
                   <?php echo $obj->getAvaliableStyles();?>
                 </select>
                 <a href='http://championforms.com/form-templates/view' style='margin:10px 0 20px'>
                   More Styles
                 </a>
                 <a href='http://championforms.com/graphic-design/view' style='margin:10px 0 20px 10px'>
                   Form Designer
                 </a>
                 <label for='FormClass'>
                   <?php echo EasyContactFormsT::get('FormClass');?>
                   <span id='FormClassHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                 </label>
                 <input type='hidden' id='FormClassHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_FormClass');?>' class='ufo-id-link'/>
                 <input type='text' id='FormClass' value='<?php echo $obj->get('FormClass');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
                 <input type='hidden' value='var c = {};c.id = "FormClass";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
                 <div id='FormClass-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
                 <label for='FormStyle' class='ufo-label-top'>
                   <?php echo EasyContactFormsT::get('FormStyle');?>
                   <span id='FormStyleHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                 </label>
                 <input type='hidden' id='FormStyleHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_FormStyle');?>' class='ufo-id-link'/>
                 <textarea id='FormStyle' class='textinput ufo-textarea ufo-formvalue' style='width:100%'><?php echo $obj->get('FormStyle');?></textarea>
                 <label for='SuccessMessageClass'>
                   <?php echo EasyContactFormsT::get('SuccessMessageClass');?>
                   <span id='SuccessMessageClassHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                 </label>
                 <input type='hidden' id='SuccessMessageClassHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_SuccessMessageClass');?>' class='ufo-id-link'/>
                 <input type='text' id='SuccessMessageClass' value='<?php echo $obj->get('SuccessMessageClass');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
                 <input type='hidden' value='var c = {};c.id = "SuccessMessageClass";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
                 <div id='SuccessMessageClass-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
                 <label for='FailureMessageClass'>
                   <?php echo EasyContactFormsT::get('FailureMessageClass');?>
                   <span id='FailureMessageClassHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                 </label>
                 <input type='hidden' id='FailureMessageClassHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_FailureMessageClass');?>' class='ufo-id-link'/>
                 <input type='text' id='FailureMessageClass' value='<?php echo $obj->get('FailureMessageClass');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
                 <input type='hidden' value='var c = {};c.id = "FailureMessageClass";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
                 <div id='FailureMessageClass-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
              </div>
            </div>
            <div class='ufo-float-right ufo-width50'>
              <div>
                 <fieldset>
                   <legend>
                     <?php echo EasyContactFormsT::get('EmailMessageFormat');?>
                   </legend>
                   <div>
                     <?php echo EasyContactFormsT::get('UnsetUseTinyMCEToUseThoseOptions');?>
                   </div>
                   <label for='SendConfirmationAsText'>
                     <input type='checkbox' id='SendConfirmationAsText' value='<?php echo $obj->SendConfirmationAsText;?>' <?php echo $obj->SendConfirmationAsTextChecked;?> class='ufo-cb checkbox ufo-formvalue' style='margin-top:0;margin-bottom:0' onchange='this.value=(this.checked)?"on":"off";'/>
                     <?php echo EasyContactFormsT::get('SendConfirmationAsText');?>
                   </label>
                   <label for='SendNotificationAsText'>
                     <input type='checkbox' id='SendNotificationAsText' value='<?php echo $obj->SendNotificationAsText;?>' <?php echo $obj->SendNotificationAsTextChecked;?> class='ufo-cb checkbox ufo-formvalue' style='margin-top:0;margin-bottom:0' onchange='this.value=(this.checked)?"on":"off";'/>
                     <?php echo EasyContactFormsT::get('SendNotificationAsText');?>
                   </label>
                 </fieldset>
              </div>
              <div>
                 <label for='FadingDelay'>
                   <?php echo EasyContactFormsT::get('FadingDelay');?>
                   <span id='FadingDelayHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                 </label>
                 <input type='hidden' id='FadingDelayHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_FadingDelay');?>' class='ufo-id-link'/>
                 <input type='text' id='FadingDelay' value='<?php echo $obj->get('FadingDelay');?>' class='textinput ufo-text ufo-formvalue'/>
                 <input type='hidden' value='var c = {};c.id = "FadingDelay";c.events = {};c.events.blur = [];c.integer={};c.integer.msg=AppMan.resources.ThisIsAnIntegerField;c.events.blur.push("integer");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
                 <div id='FadingDelay-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
              </div>
              <div>
                 <label for='MessageDelay'>
                   <?php echo EasyContactFormsT::get('MessageDelay');?>
                   <span id='MessageDelayHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
                 </label>
                 <input type='hidden' id='MessageDelayHint' value='<?php echo EasyContactFormsT::get('Hint_CustomForms_MessageDelay');?>' class='ufo-id-link'/>
                 <input type='text' id='MessageDelay' value='<?php echo $obj->get('MessageDelay');?>' class='textinput ufo-text ufo-formvalue'/>
                 <input type='hidden' value='var c = {};c.id = "MessageDelay";c.events = {};c.events.blur = [];c.integer={};c.integer.msg=AppMan.resources.ThisIsAnIntegerField;c.events.blur.push("integer");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
                 <div id='MessageDelay-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
              </div>
            </div>
            <div style='clear:left'></div>
          </div>
          <div id='StyleSheet3' class='ufo-tabs ufo-tab3'>
            <textarea id='StyleSheet' class='textnput ufo-style-sheet-text ufo-textarea ufo-formvalue' style='width:100%;height:98%'><?php echo $obj->get('StyleSheet');?></textarea>
          </div>
          <div id='ConfirmationStyleSheet3' class='ufo-tabs ufo-tab3'>
            <textarea id='ConfirmationStyleSheet' class='textnput ufo-style-sheet-text ufo-textarea ufo-formvalue' style='width:100%;height:98%'><?php echo $obj->get('ConfirmationStyleSheet');?></textarea>
          </div>
          <div id='NotificationStyleSheet3' class='ufo-tabs ufo-tab3'>
            <textarea id='NotificationStyleSheet' class='textnput ufo-style-sheet-text ufo-textarea ufo-formvalue' style='width:100%;height:98%'><?php echo $obj->get('NotificationStyleSheet');?></textarea>
          </div>
        </div>
      </div>
      <div id='CustomFormsEntries1' class='ufo-tabs ufo-tab1'>
        <input type='hidden' value='AppMan.initRedirect("CustomFormsEntries1", {specialfilter:"[{\"property\":\"CustomForms\", \"value\":{\"values\":[<?php echo $obj->get('id');?>]}}]", viewTarget:"CustomFormsEntriesDiv", t:"CustomFormsEntries", m:"viewDetailed"}, [{property:"CustomForms", value:{values:[<?php echo $obj->get('id');?>]}}])' class='ufo-eval'/>
        <div id='CustomFormsEntriesDiv' class='innerview'></div>
      </div>
    </div>
  </div>
  <div>
    <div id='buttons' class='ufo-id-link'>
      <div class='ufo-float-left'>
        <?php echo EasyContactFormsIHTML::getButton(
          array(
            'id' => "OK",
            'label' => EasyContactFormsT::get('OK'),
            'events' => " onclick='ufo.save($obj->jsconfig)'",
            'iclass' => " class='icon_button_save ufo-id-link' ",
            'bclass' => "button internalimage",
          )
        );?>
        <input type='hidden' value='var c = {};c.id = "OK";AppMan.addSubmit(c);' class='ufo-eval'/>
      </div>
      <div class='ufo-float-left'>
        <?php echo EasyContactFormsIHTML::getButton(
          array(
            'id' => "Apply",
            'label' => EasyContactFormsT::get('Apply'),
            'events' => " onclick='ufo.apply($obj->jsconfig)'",
            'iclass' => " class='icon_button_apply ufo-id-link' ",
            'bclass' => "button internalimage",
          )
        );?>
        <input type='hidden' value='var c = {};c.id = "Apply";AppMan.addSubmit(c);' class='ufo-eval'/>
      </div>
      <div class='ufo-float-left'>
        <?php echo EasyContactFormsIHTML::getButton(
          array(
            'label' => EasyContactFormsT::get('Preview'),
            'events' => " onclick='ufoCf.preview(this, $obj->jsconfig)'",
            'iclass' => " class='icon_preview' ",
            'bclass' => "button internalimage",
          )
        );?>
      </div>
      <div class='ufo-float-left'>
        <?php echo EasyContactFormsIHTML::getButton(
          array(
            'label' => EasyContactFormsT::get('Copy'),
            'events' => " onclick='ufo.copy($obj->jsconfig)'",
            'iclass' => " class='icon_button_copy' ",
            'bclass' => "button internalimage",
          )
        );?>
      </div>
      <div class='ufo-float-left'>
        <?php echo EasyContactFormsIHTML::getButton(
          array(
            'label' => EasyContactFormsT::get('Back'),
            'events' => " onclick='ufo.back()'",
            'iclass' => " class='icon_button_back' ",
            'bclass' => "button internalimage",
          )
        );?>
      </div>
      <div class='ufo-float-left'>
        <?php 
          $query = "SELECT Options.Value FROM #wp__easycontactforms_options AS Options WHERE Options.Description = 'customforms_main_form_buttons'";
          $plugs = EasyContactFormsDB::getObjects($query);
          foreach ($plugs as $plug) {
            include ABSPATH . $plug->Value;
          }
        ?>
      </div>
      <div style='clear:left'></div>
    </div>
  </div><?php

EasyContactFormsLayout::getFormBodyFooter();
