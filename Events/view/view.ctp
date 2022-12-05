<div class="events view">
<h2><?php  __('Event');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Web Welcome Page'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['web_welcome_page']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Web Infomation Page'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['web_infomation_page']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Start Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['start_date']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('End Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['end_date']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Updated'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['updated']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $event['Event']['created']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Event', true), array('action' => 'edit', $event['Event']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Event', true), array('action' => 'delete', $event['Event']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $event['Event']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Events', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Event', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Event Booth Types', true), array('controller' => 'event_booth_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Event Booth Type', true), array('controller' => 'event_booth_types', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Event Exhibitor Categories', true), array('controller' => 'event_exhibitor_categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Event Exhibitor Category', true), array('controller' => 'event_exhibitor_categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Event Files', true), array('controller' => 'event_files', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Event File', true), array('controller' => 'event_files', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Exhibition Registrations', true), array('controller' => 'exhibition_registrations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Exhibition Registration', true), array('controller' => 'exhibition_registrations', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Event Booth Types');?></h3>
	<?php if (!empty($event['EventBoothType'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Event Id'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __('Description'); ?></th>
		<th><?php __('Updated'); ?></th>
		<th><?php __('Created'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($event['EventBoothType'] as $eventBoothType):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $eventBoothType['id'];?></td>
			<td><?php echo $eventBoothType['event_id'];?></td>
			<td><?php echo $eventBoothType['name'];?></td>
			<td><?php echo $eventBoothType['description'];?></td>
			<td><?php echo $eventBoothType['updated'];?></td>
			<td><?php echo $eventBoothType['created'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'event_booth_types', 'action' => 'view', $eventBoothType['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'event_booth_types', 'action' => 'edit', $eventBoothType['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'event_booth_types', 'action' => 'delete', $eventBoothType['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $eventBoothType['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Event Booth Type', true), array('controller' => 'event_booth_types', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Event Exhibitor Categories');?></h3>
	<?php if (!empty($event['EventExhibitorCategory'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Event Id'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __('Description'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($event['EventExhibitorCategory'] as $eventExhibitorCategory):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $eventExhibitorCategory['id'];?></td>
			<td><?php echo $eventExhibitorCategory['event_id'];?></td>
			<td><?php echo $eventExhibitorCategory['name'];?></td>
			<td><?php echo $eventExhibitorCategory['description'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'event_exhibitor_categories', 'action' => 'view', $eventExhibitorCategory['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'event_exhibitor_categories', 'action' => 'edit', $eventExhibitorCategory['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'event_exhibitor_categories', 'action' => 'delete', $eventExhibitorCategory['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $eventExhibitorCategory['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Event Exhibitor Category', true), array('controller' => 'event_exhibitor_categories', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Event Files');?></h3>
	<?php if (!empty($event['EventFile'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Event Id'); ?></th>
		<th><?php __('File Name'); ?></th>
		<th><?php __('File Meta'); ?></th>
		<th><?php __('Updated'); ?></th>
		<th><?php __('Created'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($event['EventFile'] as $eventFile):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $eventFile['id'];?></td>
			<td><?php echo $eventFile['event_id'];?></td>
			<td><?php echo $eventFile['file_name'];?></td>
			<td><?php echo $eventFile['file_meta'];?></td>
			<td><?php echo $eventFile['updated'];?></td>
			<td><?php echo $eventFile['created'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'event_files', 'action' => 'view', $eventFile['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'event_files', 'action' => 'edit', $eventFile['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'event_files', 'action' => 'delete', $eventFile['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $eventFile['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Event File', true), array('controller' => 'event_files', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Exhibition Registrations');?></h3>
	<?php if (!empty($event['ExhibitionRegistration'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Event Id'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Event Category Id'); ?></th>
		<th><?php __('Onsite Contact Firstname'); ?></th>
		<th><?php __('Onsite Contact Lastname'); ?></th>
		<th><?php __('Onsite Contact Tel'); ?></th>
		<th><?php __('Onsite Contact Fax'); ?></th>
		<th><?php __('Booth No'); ?></th>
		<th><?php __('Booth Type'); ?></th>
		<th><?php __('Updated'); ?></th>
		<th><?php __('Created'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($event['ExhibitionRegistration'] as $exhibitionRegistration):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $exhibitionRegistration['id'];?></td>
			<td><?php echo $exhibitionRegistration['event_id'];?></td>
			<td><?php echo $exhibitionRegistration['user_id'];?></td>
			<td><?php echo $exhibitionRegistration['event_category_id'];?></td>
			<td><?php echo $exhibitionRegistration['onsite_contact_firstname'];?></td>
			<td><?php echo $exhibitionRegistration['onsite_contact_lastname'];?></td>
			<td><?php echo $exhibitionRegistration['onsite_contact_tel'];?></td>
			<td><?php echo $exhibitionRegistration['onsite_contact_fax'];?></td>
			<td><?php echo $exhibitionRegistration['booth_no'];?></td>
			<td><?php echo $exhibitionRegistration['booth_type'];?></td>
			<td><?php echo $exhibitionRegistration['updated'];?></td>
			<td><?php echo $exhibitionRegistration['created'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'exhibition_registrations', 'action' => 'view', $exhibitionRegistration['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'exhibition_registrations', 'action' => 'edit', $exhibitionRegistration['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'exhibition_registrations', 'action' => 'delete', $exhibitionRegistration['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $exhibitionRegistration['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Exhibition Registration', true), array('controller' => 'exhibition_registrations', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
