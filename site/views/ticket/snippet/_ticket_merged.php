<?php if ($this->merged->ticket_count > 0): ?>
	<div class="alert alert-info" style="margin-right: 120px;">
		<p><?php echo JText::_('TICKET_MERGED_NOTICE'); ?></p>
		<ul>
			<?php foreach ($this->merged->tickets as $mt): ?>
				<li><?php echo $mt->reference; ?> - <?php echo $mt->title; ?></li>	
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>

<?php if ($this->ticket->merged > 0): ?>
	<div class="alert alert-error">
		<p><?php echo JText::_('TICKET_MERGED_NOTICE_INTO'); ?></p>
		<ul>
			<?php 
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__limeticket_ticket_ticket WHERE id = " . $db->escape($this->ticket->merged);
			$db->setQuery($qry);
			$merged = $db->loadObject();
			?>
				<li>	
					<?php if (JFactory::getUser()->id > 0): ?>
						<a href="<?php echo LIMETICKETRoute::_('index.php?option=com_limeticket&view=ticket&layout=view&ticketid=' . $merged->id . "&Itemid=" . LIMETICKET_Input::getInt('Itemid'), false); ?>">
							<?php echo $merged->reference; ?> - <?php echo $merged->title; ?>
						</a>
					<?php else: ?>
						<?php echo $merged->reference; ?> - <?php echo $merged->title; ?>
					<?php endif; ?>
				</li>	
		</ul>
	</div>
<?php endif; ?>