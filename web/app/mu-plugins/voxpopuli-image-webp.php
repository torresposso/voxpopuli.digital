<?php
/**
 * Plugin Name: VoxPopuli WebP Upload Converter
 * Description: Automatically converts uploaded JPEG/PNG images to WebP format on upload and generates WebP thumbnails.
 * Version: 1.0.0
 * Author: Antigravity
 * License: MIT
 */

add_filter('wp_handle_upload', function ($upload, $context) {
    // Only process if it is a JPEG or PNG image
    $mime_type = $upload['type'] ?? '';
    if (!in_array($mime_type, ['image/jpeg', 'image/jpg', 'image/png'])) {
        return $upload;
    }

    $file_path = $upload['file'] ?? '';
    if (empty($file_path) || !file_exists($file_path)) {
        return $upload;
    }

    // Get file info
    $info = pathinfo($file_path);
    $dirname = $info['dirname'] ?? '';
    $filename = $info['filename'] ?? '';
    if (empty($dirname) || empty($filename)) {
        return $upload;
    }

    $webp_path = $dirname . '/' . $filename . '.webp';

    // Check if the destination file already exists (append unique suffix if needed)
    $counter = 1;
    while (file_exists($webp_path)) {
        $webp_path = $dirname . '/' . $filename . '-' . $counter . '.webp';
        $counter++;
    }

    // Use WordPress Image Editor abstraction to handle conversion
    $image_editor = wp_get_image_editor($file_path);
    if (is_wp_error($image_editor)) {
        return $upload;
    }

    // Convert and save as WebP with 82% quality (matching WP core default)
    $saved = $image_editor->save($webp_path, 'image/webp', ['quality' => 82]);
    if (is_wp_error($saved)) {
        return $upload;
    }

    // Delete the original file to save disk space
    @unlink($file_path);

    // Update the upload data array with WebP info
    $upload['file'] = $webp_path;
    $upload['url'] = str_replace(basename($file_path), basename($webp_path), $upload['url'] ?? '');
    $upload['type'] = 'image/webp';

    return $upload;
}, 10, 2);
