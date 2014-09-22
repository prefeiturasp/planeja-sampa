<?php
/**
 * @file
 *
 * 	EasyContactFormsDashBoardView DashBoardView form html template
 *
 * 	@see EasyContactFormsDashBoardView::getDashBoardViewForm()
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */


EasyContactFormsLayout::getFormHeader('ufo-formpage ufo-dashboardviewform ufo-' . strtolower($obj->type));
echo EasyContactFormsUtils::getViewDescriptionLabel(EasyContactFormsT::get('DashBoardView'));
EasyContactFormsLayout::getFormHeader2Body();

?>
  <div>
    <div>
      <div style='width:300px;float:left'>
        <div class='ufo-dashboard-header'>
          <?php echo EasyContactFormsT::get('UserStatistics');?>
        </div>
        <div>
          <?php $obj->getUserStatistics();?>
        </div>
      </div>
      <div style='margin-left:305px'>
        <div class='ufo-float-left'>
          <div style='width:160px;padding:7px 0'>
            <div>
              <a href='http://championforms.com/home/view' style='width:156px;margin-left:-5px;background:url(http://championforms.com/wp-content/uploads/banners/championforms130.jpg) center center no-repeat;height:130px;display:block;border-radius:4px;margin-top:-1px;border:1px solid #ddd'>
                 
              </a>
            </div>
          </div>
        </div>
        <div class='ufo-float-left'>
          <div style='width:180px;padding:0 10px'>
            <div>
              <label><?php echo EasyContactFormsT::get('VisitChampionForms');?></label>
              <a href='http://championforms.com/home/easy'>
                 Form Templates, One Minute Designer, Video Tutorials, and more
              </a>
            </div>
            <div>
              <a href='http://championforms.com/faq/view' style='line-height:1.7em'>
                 Frequently Asked Questions
              </a>
            </div>
            <div>
              <label><?php echo EasyContactFormsT::get('VisitEasyContactForms');?></label>
              <a href='http://easy-contact-forms.com'>Information about Web Forms</a>
            </div>
          </div>
        </div>
        <div class='ufo-float-left'>
          <div style='width:190px;padding:10px 0 0 10px'>
            <div>
              <div style='height:130px;overflow:auto;font-size:11px'>
                 <div style='font-weight:bold'>
                   Current version: 1.4.7
                 </div>
                 <ol style='padding:1px'>
                   <li style='margin:0;padding:1px'>
                      Back end support form fixes
                   </li>
                   <li style='margin:0;padding:1px'>
                     Back end sample contact form fixes
                   </li>
                 </ol>
              </div>
            </div>
          </div>
        </div>
        <div style='clear:left'></div>
      </div>
      <div style='clear:both;height:1px'></div>
    </div>
    <div>
      <div class='ufo-dashboard-header'>
        <?php echo EasyContactFormsT::get('PageStatistics');?>
      </div>
      <div>
        <?php $obj->getFormPageStatistics();?>
      </div>
      <div class='ufo-dashboard-header'>
        <?php echo EasyContactFormsT::get('FormStatistics');?>
      </div>
      <div>
        <?php $obj->getFormStatistics();?>
      </div>
      <div class='ufo-dashboard-header'>
        <?php echo EasyContactFormsT::get('EntryStatistics');?>
      </div>
      <div>
        <?php $obj->getEntryStatistics();?>
      </div>
    </div>
  </div><?php

EasyContactFormsLayout::getFormBodyFooter();
