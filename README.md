php-togglSDK
============

A complete Toggl API v8 php SDK

This SDK was created by scraping the Toggl API v8 documentation.  This is a work in progress.
All methods should be functional, but as of now there are no written tests.
Only basic functionality has been confirmed as working.

How to use:
Include the Toggl/Toggl.php file
Call Toggl::setKey($apiKey)

All API endpoints are available as static methods inside of each class.  There is one class for each "chapter"
listed in the API documentation, and one class for all reports.

All methods return a PHP array with the data returned by the API

Examples:
GET methods (no data post)
$workspaces = TogglWorkspace::getWorkspaces();
$projects = TogglWorkspace::getWorkspaceProjects($workspaceId);

POST methods
TogglClient::createAClient(array('name' => 'John Doe', 'wid' => $workspaceId, 'notes' => 'A new client'));

Reports
$data = TogglReport::weekly(array('user_agent' => 'email@example.com', 'workspace_id' => $workspaceId, 'since' => date('Y-m-d', strtotime(' - 1 month')), 'until' => date('Y-m-d')));
