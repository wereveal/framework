<?php
$directory = '/Users/wer/Documents/active_projects/ritc/libraryDev/cache';

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory), RecursiveIteratorIterator::LEAVES_ONLY);
$it->rewind();
$starts_with = 'fred';
$ends_with = 'txt';
$starts_not = '';
$ends_not = '';
while($it->valid()) {
    if (!$it->isDot()) {
        if (str_ends_with($it->getFileName(), $ends_with) && str_starts_with($it->getFileName(), $starts_with))  {
            echo 'This matches starts and ends.' . "\n";
        }
        $file_w_path = $it->current();
        echo 'Current: ' . $file_w_path . "\n";
        echo 'Filename: ' . $it->getFileName() . "\n";
        echo 'Filename Base: ' . $it->getBasename('.' . $it->getExtension()) . "\n";
        echo 'extension: ' . $it->getExtension() . "\n";
        echo 'SubPathName: ' . $it->getSubPathName() . "\n";
        echo 'SubPath:     ' . $it->getSubPath() . "\n";
        $file_t = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file_w_path);
        echo 'Real File Type: ' . $file_t . "\n\n";
        echo "Trying to figure out FileInfo Constants\n";
        echo "FILEINFO_NONE: " . finfo_file(finfo_open(FILEINFO_NONE), $file_w_path) . "\n";
        echo "FILEINFO_SYMLINK: " . finfo_file(finfo_open(FILEINFO_SYMLINK), $file_w_path) . "\n";
        echo "FILEINFO_MIME_TYPE: " . finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file_w_path) . "\n";
        echo "FILEINFO_MIME_ENCODING: " . finfo_file(finfo_open(FILEINFO_MIME_ENCODING), $file_w_path) . "\n";
        echo "FILEINFO_MIME: " . finfo_file(finfo_open(FILEINFO_MIME), $file_w_path) . "\n";
        echo "FILEINFO_DEVICES: " . finfo_file(finfo_open(FILEINFO_DEVICES), $file_w_path) . "\n";
        echo "FILEINFO_CONTINUE: " . finfo_file(finfo_open(FILEINFO_CONTINUE), $file_w_path) . "\n";
        echo "FILEINFO_PRESERVE_ATIME: " . finfo_file(finfo_open(FILEINFO_PRESERVE_ATIME), $file_w_path) . "\n";
        echo "FILEINFO_RAW: " . finfo_file(finfo_open(FILEINFO_RAW), $file_w_path) . "\n";
        echo "FILEINFO_EXTENSION: " . finfo_file(finfo_open(FILEINFO_EXTENSION), $file_w_path) . "\n\n\n";
    }
    $it->next();
}
?>
