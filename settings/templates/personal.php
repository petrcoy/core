<?php /**
 * Copyright (c) 2011, Robin Appelman <icewind1991@gmail.com>
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
 */?>

<div class="clientsbox">
	<h2><?php p($l->t('Get the apps to sync your files'));?></h2>
	<a href="<?php p($_['clients']['desktop']); ?>" target="_blank">
		<img src="<?php print_unescaped(OCP\Util::imagePath('core', 'desktopapp.png')); ?>" />
	</a>
	<a href="<?php p($_['clients']['android']); ?>" target="_blank">
		<img src="<?php print_unescaped(OCP\Util::imagePath('core', 'googleplay.png')); ?>" />
	</a>
	<a href="<?php p($_['clients']['ios']); ?>" target="_blank">
		<img src="<?php print_unescaped(OCP\Util::imagePath('core', 'appstore.png')); ?>" />
	</a>
	<?php if(OC_APP::isEnabled('firstrunwizard')) {?>
	<center><a class="button" href="#" id="showWizard"><?php p($l->t('Show First Run Wizard again'));?></a></center>
	<?php }?>
</div>


<div id="quota" class="personalblock">
	<div style="width:<?php p($_['usage_relative']);?>%;">
		<p id="quotatext">
			<?php print_unescaped($l->t('You have used <strong>%s</strong> of the available <strong>%s</strong>',
			array($_['usage'], $_['total_space'])));?>
		</p>
	</div>
</div>


<?php
if($_['passwordChangeSupported']) {
?>
<form id="passwordform">
	<fieldset class="personalblock">
		<legend><strong><?php p($l->t('Password'));?></strong></legend>
		<div id="passwordchanged"><?php echo $l->t('Your password was changed');?></div>
		<div id="passworderror"><?php echo $l->t('Unable to change your password');?></div>
		<input type="password" id="pass1" name="oldpassword" placeholder="<?php echo $l->t('Current password');?>" />
		<input type="password" id="pass2" name="password"
			placeholder="<?php echo $l->t('New password');?>" data-typetoggle="#personal-show" />
		<input type="checkbox" id="personal-show" name="show" /><label for="personal-show"></label>
		<input id="passwordbutton" type="submit" value="<?php echo $l->t('Change password');?>" />
	</fieldset>
</form>
<?php
}
?>

<?php
if($_['displayNameChangeSupported']) {
?>
<form id="displaynameform">
	<fieldset class="personalblock">
		<legend><strong><?php echo $l->t('Display Name');?></strong></legend>
		<input type="text" id="displayName" name="displayName" value="<?php p($_['displayName'])?>" />
        <span class="msg"></span>
		<input type="hidden" id="oldDisplayName" name="oldDisplayName" value="<?php p($_['displayName'])?>" />
	</fieldset>
</form>
<?php
}
?>

<?php
if($_['passwordChangeSupported']) {
?>
<form id="lostpassword">
	<fieldset class="personalblock">
		<legend><strong><?php p($l->t('Email'));?></strong></legend>
		<input type="text" name="email" id="email" value="<?php p($_['email']); ?>"
			placeholder="<?php p($l->t('Your email address'));?>" /><span class="msg"></span><br />
		<em><?php p($l->t('Fill in an email address to enable password recovery'));?></em>
		<?php if($_['avatar'] === "gravatar") {
			print_unescaped($l->t('<br><em>Your Email will be used for your gravatar<em>'));
		} ?>
	</fieldset>
</form>
<?php
}
?>

<?php if ($_['avatar'] !== "none"): ?>
<form id="avatar" method="post" action="<?php p(\OC_Helper::linkToRoute('settings_ajax_newavatar')); ?>">
	<fieldset class="personalblock">
		<legend><strong><?php p($l->t('Profile Image')); ?></strong></legend>
		<img src="<?php print_unescaped(link_to('', 'avatar.php').'?user='.OC_User::getUser().'&size=128'); ?>"><br>
		<?php if ($_['avatar'] === "local"): ?>
			<em><?php p($l->t('Your profile image has to be a square and either a PNG or JPG image')); ?></em><br>
			<div class="inlineblock button" id="uploadavatarbutton"><?php p($l->t('Upload a new image')); ?></div>
			<input type="file" class="hidden" name="files[]" id="uploadavatar">
			<div class="inlineblock button" id="selectavatar"><?php p($l->t('Select a new image from your files')); ?></div>
			<div class="inlineblock button" id="removeavatar"><?php p($l->t('Remove my image')); ?></div>
		<?php elseif ($_['avatar'] === "gravatar"): ?>
			<em><?php p($l->t('Your profile image is provided by gravatar, which is based on your Email.')); ?></em>
			<div class?"inlineblock button" id="overridegravatar"><?php p($l->t('Use my local avatar instead')); ?></div>
		<?php else: ?>
			<em><?php p($l->t('Your profile image is provided by a custom service, ask your administrator, on how to change your image.')); ?></em>
		<?php endif; ?>
	</fieldset>
</form>
<?php endif; ?>

<form>
	<fieldset class="personalblock">
		<legend><strong><?php p($l->t('Language'));?></strong></legend>
		<select id="languageinput" class="chzen-select" name="lang" data-placeholder="<?php p($l->t('Language'));?>">
		<option value="<?php p($_['activelanguage']['code']);?>"><?php p($_['activelanguage']['name']);?></option>
		<?php foreach($_['commonlanguages'] as $language):?>
			<option value="<?php p($language['code']);?>"><?php p($language['name']);?></option>
		<?php endforeach;?>
		<optgroup label="––––––––––"><option class="languagedivider">-</option></optgroup>
		<?php foreach($_['languages'] as $language):?>
			<option value="<?php p($language['code']);?>"><?php p($language['name']);?></option>
		<?php endforeach;?>
		</select>
		<?php if (OC_Util::getEditionString() === ''): ?>
		<a href="https://www.transifex.com/projects/p/owncloud/team/<?php p($_['activelanguage']['code']);?>/"
			target="_blank"><em><?php p($l->t('Help translate'));?></em></a>
		<?php endif; ?>
	</fieldset>
</form>

<fieldset class="personalblock">
	<legend><strong><?php p($l->t('WebDAV'));?></strong></legend>
	<code><?php print_unescaped(OC_Helper::linkToRemote('webdav')); ?></code><br />
	<em><?php print_unescaped($l->t('Use this address to <a href="%s/server/5.0/user_manual/files/files.html" target="_blank">access your Files via WebDAV</a>', array($theme->getDocBaseUrl())));?></em>
</fieldset>

<?php foreach($_['forms'] as $form) {
	print_unescaped($form);
};?>

<fieldset class="personalblock">
	<legend><strong><?php p($l->t('Version'));?></strong></legend>
	<strong><?php p($theme->getName()); ?></strong> <?php p(OC_Util::getVersionString()); ?><br/>
<?php if (OC_Util::getEditionString() === ''): ?>
	<?php print_unescaped($l->t('Developed by the <a href="http://ownCloud.org/contact" target="_blank">ownCloud community</a>, the <a href="https://github.com/owncloud" target="_blank">source code</a> is licensed under the <a href="http://www.gnu.org/licenses/agpl-3.0.html" target="_blank"><abbr title="Affero General Public License">AGPL</abbr></a>.')); ?>
<?php endif; ?>
</fieldset>
<fieldset class="personalblock credits-footer">
<p>
	<?php print_unescaped($theme->getShortFooter()); ?>
</p>
</fieldset>
