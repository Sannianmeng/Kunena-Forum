<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Template.Crypsis
 * @subpackage      Layout.Category
 *
 * @copyright       Copyright (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/

/** @var KunenaForumCategory $section */
/** @var KunenaForumCategory $category */
/** @var KunenaForumCategory $subcategory */

defined('_JEXEC') or die;

if ($this->config->enableforumjump)
{
	echo $this->subLayout('Widget/Forumjump')->set('categorylist', $this->categorylist);
}

$mmm    = 0;
$config = KunenaFactory::getTemplate()->params;

if ($config->get('displayModule'))
{
	echo $this->subLayout('Widget/Module')->set('position', 'kunena_index_top');
}

foreach ($this->sections as $section) :
	$markReadUrl = $section->getMarkReadUrl();

	if ($config->get('displayModule'))
	{
		echo $this->subLayout('Widget/Module')->set('position', 'kunena_section_top_' . ++$mmm);
	} ?>

	<div class="kfrontend">
		<h2 class="btn-toolbar pull-right">
			<?php if (count($this->sections) > 1) : ?>
				<button class="btn btn-default btn-sm <?php echo KunenaIcons::collapse(); ?>" type="button" data-toggle="collapse"
				        data-target="#section<?php echo $section->id; ?>" aria-expanded="false"
				        aria-controls="section<?php echo $section->id; ?>"></button>
			<?php endif; ?>
		</h2>

		<h1 class="btn-link">
			<?php echo $this->getCategoryLink($section, $this->escape($section->name), null, KunenaTemplate::getInstance()->tooltips(), false, false); ?>
			<small class="hidden-xs nowrap">
				<?php if ($section->getTopics() > 0) : ?>
					(<?php echo JText::plural('COM_KUNENA_X_TOPICS_MORE', $this->formatLargeNumber($section->getTopics())); ?>)
				<?php else : ?>
					(<?php echo JText::_('COM_KUNENA_X_TOPICS_0'); ?>)
				<?php endif; ?>
			</small>
		</h1>

		<div class="collapse in section <?php if (!empty($section->class)) : ?>section<?php echo $this->escape($section->class_sfx); ?><?php endif;?> in collapse" id="section<?php echo $section->id; ?>">
			<table class="table<?php echo KunenaTemplate::getInstance()->borderless();?>">
				<?php if (!empty($section->description)) : ?>
					<thead class="hidden-xs">
					<tr>
						<td colspan="3">
							<div class="well well-sm"><?php echo $section->displayField('description'); ?></div>
						</td>
					</tr>
					</thead>
				<?php endif; ?>

				<?php if ($section->isSection() && empty($this->categories[$section->id]) && empty($this->more[$section->id])) : ?>
					<tr>
						<td>
							<h4>
								<?php echo JText::_('COM_KUNENA_GEN_NOFORUMS'); ?>
							</h4>
						</td>
					</tr>
				<?php else : ?>
					<?php if (!empty($this->categories[$section->id])) : ?>
						<tr>
							<td colspan="2" class="hidden-xs">
								<?php echo JText::_('COM_KUNENA_GEN_CATEGORY'); ?>
							</td>
							<td colspan="1" class="hidden-xs post-info">
								<?php echo JText::_('COM_KUNENA_GEN_LAST_POST'); ?>
							</td>
						</tr>
					<?php endif; ?>
					<?php
					foreach ($this->categories[$section->id] as $category) : ?>
						<tr class="category<?php echo $this->escape($category->class_sfx); ?>" id="category<?php echo $category->id; ?>">
							<td class="col-md-1 text-center hidden-xs">
								<?php echo $this->getCategoryLink($category, $this->getCategoryIcon($category), '', null, true, false); ?>
							</td>
							<td class="col-md-8">
								<div>
									<h3>
										<?php echo $this->getCategoryLink($category, $category->name, null, KunenaTemplate::getInstance()->tooltips(), true, false); ?>
										<small class="hidden-xs nowrap">
											<?php if ($category->getTopics() > 0) : ?>
												(<?php echo JText::plural('COM_KUNENA_X_TOPICS_MORE', $this->formatLargeNumber($category->getTopics())); ?>)
											<?php else : ?>
												(<?php echo JText::_('COM_KUNENA_X_TOPICS_0'); ?>)
											<?php endif; ?>
											<span>
												<?php if (($new = $category->getNewCount()) > 0) : ?>
													<sup class="knewchar"> (<?php echo $new . JText::_('COM_KUNENA_A_GEN_NEWCHAR') ?>)</sup>
												<?php endif; ?>
												<?php if ($category->locked) : ?>
													<span <?php echo KunenaTemplate::getInstance()->tooltips(true);?> title="<?php echo JText::_('COM_KUNENA_LOCKED_CATEGORY') ?>"><?php echo KunenaIcons::lock(); ?></span>
												<?php endif; ?>
												<?php if ($category->review) : ?>
													<span <?php echo KunenaTemplate::getInstance()->tooltips(true);?> title="<?php echo JText::_('COM_KUNENA_GEN_MODERATED') ?>"><?php echo KunenaIcons::shield(); ?></span>
												<?php endif; ?>

												<?php if (KunenaFactory::getConfig()->enablerss) : ?>
													<a href="<?php echo $this->getCategoryRSSURL($category->id); ?>" rel="alternate" type="application/rss+xml">
														 <?php echo KunenaIcons::rss(); ?>
													</a>
												<?php endif; ?>
											</span>
										</small>
									</h3>
								</div>

								<?php if (!empty($category->description)) : ?>
									<div class="hidden-xs header-desc"><?php echo $category->displayField('description'); ?></div>
								<?php endif; ?>

								<?php
								// Display subcategories
								if (!empty($this->categories[$category->id])) : ?>
									<div>
										<ul class="list-inline">

											<?php foreach ($this->categories[$category->id] as $subcategory) : ?>
												<li>
													<?php $totaltopics = $subcategory->getTopics() > 0 ? JText::plural('COM_KUNENA_X_TOPICS_MORE', $this->formatLargeNumber($subcategory->getTopics())) : JText::_('COM_KUNENA_X_TOPICS_0'); ?>

													<?php echo $this->getCategoryLink($subcategory, $this->getSmallCategoryIcon($subcategory), '', null, true, false) . $this->getCategoryLink($subcategory, '', null, KunenaTemplate::getInstance()->tooltips(), true, false) . '<small class="hidden-phone muted"> ('
														. $totaltopics . ')</small>';

													if (($new = $subcategory->getNewCount()) > 0)
													{
														echo '<sup class="knewchar">(' . $new . ' ' . JText::_('COM_KUNENA_A_GEN_NEWCHAR') . ')</sup>';
													}
													?>
												</li>
											<?php endforeach; ?>

											<?php if (!empty($this->more[$category->id])) : ?>
												<li>
													<?php echo $this->getCategoryLink($category, JText::_('COM_KUNENA_SEE_MORE'), null, KunenaTemplate::getInstance()->tooltips(), true, false); ?>
													<small class="hidden-xs muted">
														(<?php echo JText::sprintf('COM_KUNENA_X_HIDDEN', (int) $this->more[$category->id]); ?>)
													</small>
												</li>
											<?php endif; ?>

										</ul>
									</div>
								<?php endif; ?>

								<?php if ($category->getmoderators() && KunenaConfig::getInstance()->listcat_show_moderators) : ?>
									<br/>
									<div class="moderators">
										<?php
										// get the Moderator list for display
										$modslist = array();
										foreach ($category->getmoderators() as $moderator)
										{
											$modslist[] = KunenaFactory::getUser($moderator)->getLink(null, null, '', null, KunenaTemplate::getInstance()->tooltips());
										}

										echo JText::_('COM_KUNENA_MODERATORS') . ': ' . implode(', ', $modslist);
										?>
									</div>
								<?php endif; ?>

								<?php if (!empty($this->pending[$category->id])) : ?>
									<div class="alert alert-warning" role="alert" style="margin-top:10px;">
										<a class="alert-link"
										   href="<?php echo KunenaRoute::_('index.php?option=com_kunena&view=topics&layout=posts&mode=unapproved&userid=0&catid=' . intval($category->id)); ?>"
										   title="<?php echo JText::_('COM_KUNENA_SHOWCAT_PENDING') ?>"
										   rel="nofollow"><?php echo intval($this->pending[$category->id]) . ' ' . JText::_('COM_KUNENA_SHOWCAT_PENDING') ?></a>
									</div>
								<?php endif; ?>
							</td>

							<?php $last = $category->getLastTopic(); ?>

							<?php if ($last->exists()) :
								$author = $last->getLastPostAuthor();
								$time = $last->getLastPostTime();
								$this->ktemplate = KunenaFactory::getTemplate();
								$avatar = $this->config->avataroncat ? $author->getAvatarImage($this->ktemplate->params->get('avatarType'), 'post') : null;
								?>

								<td class="col-md-3 hidden-xs">
										<div class="col-md-12">
											<?php if ($avatar) : ?>
												<div class="col-md-4">
													<?php echo $author->getLink($avatar, null, '', '', KunenaTemplate::getInstance()->tooltips(), $category->id); ?>
												</div>
												<div class="col-md-8">
											<?php else : ?>
												<div class="col-md-12">
											<?php endif; ?>
												<span><?php echo $this->getLastPostLink($category,null, null, KunenaTemplate::getInstance()->tooltips(), null, false, true) ?></span>
												<br>
												<span><?php echo JText::sprintf('COM_KUNENA_BY_X', $author->getLink(null, null, '', '', KunenaTemplate::getInstance()->tooltips(), $category->id)); ?></span>
												<br>
												<span><?php echo $time->toKunena('config_post_dateformat'); ?></span>
											</div>
										</div>
									</div>
								</td>
							<?php else : ?>
								<td class="col-md-3 hidden-xs">
									<div class="last-post-message">
										<?php echo JText::_('COM_KUNENA_X_TOPICS_0'); ?>
									</div>
								</td>
							<?php endif; ?>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				<?php if (!empty($this->more[$section->id])) : ?>
					<tr>
						<td colspan="3">
							<h4>
								<?php echo $this->getCategoryLink($section, JText::sprintf('COM_KUNENA_SEE_ALL_SUBJECTS')); ?>
								<small>(<?php echo JText::sprintf('COM_KUNENA_X_HIDDEN', (int) $this->more[$section->id]); ?>)</small>
							</h4>
						</td>
					</tr>
				<?php endif; ?>

			</table>
		</div>
	</div>
	<!-- Begin: Category Module Position -->
	<?php
	if ($config->get('displayModule'))
	{
		echo $this->subLayout('Widget/Module')->set('position', 'kunena_section_' . ++$mmm);
	} ?>
	<!-- Finish: Category Module Position -->
<?php endforeach;

if ($config->get('displayModule'))
{
	echo $this->subLayout('Widget/Module')->set('position', 'kunena_index_bottom');
}
