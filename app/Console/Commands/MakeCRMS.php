<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class MakeCRMS extends Command
{
    // Đặt tên và mô tả cho command
    protected $signature = 'make:crms {name}';
    protected $description = 'Tạo một Repository mới (trống)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->createModel();
        $this->createController();
        $this->createRepository();
        $this->createService();
    }

    public function createRepository()
    {
        // Lấy tên repository từ argument
        $name = $this->argument('name');

        // Đường dẫn thư mục lưu các repository
        $repositoryPath = app_path('Repositories');

        // Kiểm tra và tạo thư mục nếu chưa có
        if (!File::exists($repositoryPath)) {
            File::makeDirectory($repositoryPath, 0755, true);
        }

        // Đường dẫn tới file repository
        $filePath = $repositoryPath . '/' . $name . 'Repository.php';

        // Nội dung mẫu của repository (chỉ chứa class trống)
        $template = "<?php

namespace App\Repositories;

class " . $name . "Repository
{
    // Add your repository methods here
}
";

        // Kiểm tra nếu file đã tồn tại
        if (File::exists($filePath)) {
            $this->error("Repository {$name} đã tồn tại!");
            return;
        }

        // Tạo file repository mới
        File::put($filePath, $template);

        // Thông báo thành công
        $this->info("Repository {$name} đã được tạo thành công.");
    }

    public function createService()
    {
        $name = $this->argument('name');
        $servicePath = app_path('Services');
        if (!File::exists($servicePath)) {
            File::makeDirectory($servicePath, 0755, true);
        }
        $filePath = $servicePath . '/' . $name . 'Service.php';
        $template = "<?php

namespace App\Services;

class " . $name . "Service
{
    public function __construct()
    {

    }
}
";
        if (File::exists($filePath)) {
            $this->error("Service {$name} đã tồn tại!");
            return;
        }

        File::put($filePath, $template);

        // Thông báo thành công
        $this->info("Service {$name} đã được tạo thành công.");
    }

    public function createController() {
        $name = $this->argument('name');
        $controllerPath = app_path('Http/Controllers');
        if (!File::exists($controllerPath)) {
            File::makeDirectory($controllerPath, 0755, true);
        }
        $filePath = $controllerPath . '/' . $name . 'Controller.php';
        $template = "<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetTypeGroupController extends Controller
{
    public function __construct()
    {

    }
}";
        if (File::exists($filePath)) {
            $this->error("Controller {$name} đã tồn tại!");
            return;
        }

        File::put($filePath, $template);

        // Thông báo thành công
        $this->info("Controller {$name} đã được tạo thành công.");
    }

    public function createModel() {
        $name = $this->argument('name');
        $modelPath = app_path('Models');
        if (!File::exists($modelPath)) {
            File::makeDirectory($modelPath, 0755, true);
        }
        $filePath = $modelPath . '/' . $name . 'Model.php';
        $template = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class " . $name . "Model extends Model
{
    use HasFactory;
}
";
        if (File::exists($filePath)) {
            $this->error("Model {$name} đã tồn tại!");
            return;
        }

        File::put($filePath, $template);

        // Thông báo thành công
        $this->info("Model {$name} đã được tạo thành công.");
    }
}
