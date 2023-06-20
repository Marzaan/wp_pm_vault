<form class="item-form" method="post">
    <div class="row">
        <div class="col-6 mb-4">
            <label class="form-label fw-bold" for="<?php echo esc_attr( 'name' ); ?>"><?php echo esc_html( 'Name:' ); ?></label>
            <input type="text" class="form-control" placeholder="<?php echo esc_attr('Item Name'); ?>" name="<?php echo esc_attr( 'name' ); ?>" required>
        </div>
        <div class="col-6 mb-4">
            <label class="form-label fw-bold" for="<?php echo esc_attr( 'folderID' ); ?>"><?php echo esc_html( 'Folder:' ); ?></label>
            <select class="form-select" name="<?php echo esc_attr( 'folderID' ); ?>" required>
                <option value="null"><?php echo esc_html( '-- Select --' ); ?></option>
                <?php foreach ( $foldersData as $folder ) : ?>
                    <option value="<?php echo esc_attr( $folder->id ); ?>"><?php echo esc_html( $folder->foldername ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-6 mb-4">
            <label class="form-label fw-bold" for="username"><?php echo esc_html( 'Username:' ); ?></label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="<?php echo esc_attr('username'); ?>" placeholder="<?php echo esc_attr('Username'); ?>" required>
            </div>
        </div>
        <div class="col-6 mb-4">
            <label class="form-label fw-bold" for="password"><?php echo esc_html( 'Password:' ); ?></label>
            <div class="input-group mb-3">
                <input type="password" class="form-control" name="<?php echo esc_attr('password'); ?>" placeholder="<?php echo esc_attr('Password'); ?>" required>
            </div>
        </div>
    </div>
    <div class="url-fields-container row">
        <template id="url-field-template">
            <div class="col-6 mb-4">
                <label class="form-label fw-bold"><?php echo esc_html( 'URL:' ); ?></label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="https://google.com" name="<?php echo esc_attr('urls[]'); ?>">
                </div>
            </div>
        </template>
    </div>
    <div class="col-12 mb-4">
        <a class="link-info" id="new-field"><?php echo esc_html( 'Add URL' ); ?></a>
    </div>
    <div class="row">
        <div class="col-12 mb-4">
            <label class="form-label fw-bold" for="notes"><?php echo esc_html( 'Notes:' ); ?></label>
            <textarea class="form-control" name="<?php echo esc_attr('notes'); ?>" rows="3"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <label class="form-label fw-bold" for="favorite"><?php echo esc_html( 'Favorite:' ); ?></label>
            <span class="mx-1"></span>
            <input type="checkbox" class="form-check-input" name="<?php echo esc_attr('favorite'); ?>">
        </div>
    </div>
    <div class="col-12 mb-4">
        <button type="submit" name="<?php echo esc_attr('submit_item'); ?>" class="btn btn-success"><?php echo esc_html( 'Submit:' ); ?></button>
    </div>
</form>