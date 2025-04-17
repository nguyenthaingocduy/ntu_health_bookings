# Storage Link Setup

To ensure that uploaded images are properly displayed, you need to create a symbolic link from the `public/storage` directory to the `storage/app/public` directory.

Run the following command in your terminal:

```bash
php artisan storage:link
```

This command creates a symbolic link that allows public access to files in the storage/app/public folder.

## Troubleshooting

If you encounter issues with image uploads or display:

1. Make sure the storage link is created
2. Check that the `storage/app/public` directory is writable
3. Verify that the `services` directory exists in `storage/app/public` (it will be created automatically when the first image is uploaded)
4. If you're using a hosting service, make sure symbolic links are allowed

## Image Paths

The application stores images in the `storage/app/public/services` directory and references them using URLs like `/storage/services/filename.jpg`.
