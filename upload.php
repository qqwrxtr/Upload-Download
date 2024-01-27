<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $uploadDir = 'uploaded_images/';
    $uploadedFiles = $_FILES['fileToUpload'];

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $response = array();

    foreach ($uploadedFiles['name'] as $key => $name) {
        $tmpName = $uploadedFiles['tmp_name'][$key];
        $newName = $uploadDir . $name;

        if (move_uploaded_file($tmpName, $newName)) {
            $displayName = (strlen($name) > 16) ? substr($name, -16) : $name;

            // Construct the counter file path
            $counterFile = 'download_counters/' . pathinfo($newName, PATHINFO_FILENAME) . '_counter.txt';

            // Check if the download counter file exists
            if (!file_exists($counterFile)) {
                file_put_contents($counterFile, '0');
            }

            // Read the current download count
            $downloadCount = intval(file_get_contents($counterFile));

            $imageHtml = <<<HTML
<div class="flex">
    <div class="img_info">
        <img class="uploaded_image" src="$newName" alt="$name" />
        <p class="image_name">$displayName</p>
    </div>
    <div class="links" style="display: flex; justify-content: center; align-items: center; width: 50px;">
        <div class="delete" style="margin-right: 10px;">
            <a class="delete_link" href="delete.php?image=$name"><span class="material-symbols-outlined"> delete</span></a>
        </div>
        <div class="download">
            <a class="download_link" style="color: #0f0;" href="$newName" download="$displayName"><span class="material-symbols-outlined">download</span></a>
            <span class="download_counter">Downloads: $downloadCount</span>
        </div>
    </div>
</div>
HTML;

            $response[] = $imageHtml;

            // Update the download counter
            $downloadCount++;
            file_put_contents($counterFile, strval($downloadCount));
        }
    }

    $success = !empty($response);

    $responseData = [
        'success' => $success,
        'images' => $response,
    ];

    echo json_encode($responseData);
}
?>
