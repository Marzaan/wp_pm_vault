<table class="table caption-top">
    <thead class="<?php echo esc_attr('table-dark'); ?>">
        <tr>
            <th><?php echo esc_html('Name'); ?></th>
            <th><?php echo esc_html('Username'); ?></th>
            <th><?php echo esc_html('Password'); ?></th>
            <th><?php echo esc_html('URL'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($itemsData as $itemData) : ?>
            <?php $itemData->urls = json_decode($itemData->urls); ?>
            <tr>
                <td><?php echo esc_html($itemData->name); ?></td>
                <td><?php echo esc_html($itemData->username); ?></td>
                <td><?php echo esc_html($itemData->password); ?></td>
                <td><?php echo esc_html($itemData->urls[0]); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>