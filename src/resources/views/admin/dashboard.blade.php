<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บ่อปลา</title>
    @vite(['resources/css/app.scss','resources/js/app.js'])
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=person" />
</head>
<body>
    <div id="app">

    <a href="/"><img src="/storage/assets/logo.svg" alt="Logo" class="logo" width="130"></a>
    <header class="d-flex justify-content-between align-items-center p-4">
      <div>
        <div class="dropdown ms-2">
          <button class="dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="/storage/assets/person.svg"> {{ Auth::check() ? Auth::user()->username : 'Guest' }}
          </button>
          <ul class="dropdown-menu" aria-labelledby="userMenuButton">
            <li><a href="/login"><div v-if="!localUser" @click="navigateTo('')">เข้าสู่ระบบ</div></a></li>
            <li><a href="/login"><div v-if="!localUser" @click="navigateTo('/register')" class="">สมัครสมาชิก</div></a></li>
            <li><a href="/inventory"><div v-if="localUser" @click="navigateTo('/inventory')">ถังเก็บปลา</div></a></li>
            <li><a href="/" @click.prevent="logout"><div v-if="localUser">ออกจากระบบ</div></a></li>
            <li><a href="/admin"><div v-if="localUser && localUser.role === 'admin'">เข้าหลังบ้าน</div></a></li>
          </ul>
        </div>
      </div>
    </header>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <div class="d-flex justify-content-between align-items-center ">
                            <h3>บ่อปลา</h3>
                            <div>
                                <a href="{{ route('admin.add-fish') }}" class="btn btn-primary mb-3">เพิ่มปลาใหม่</a>
                                <a href="{{ route('admin.rods.index') }}" class="btn btn-secondary mb-3">จัดการเบ็ดตกปลา</a>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ไอดีปลา</th>
                                    <th>ชื่อปลา</th>
                                    <th>ระดับ</th>
                                    <th>มูลค่า</th>
                                    <th>มูลค่า Token</th>
                                    <th>รูปภาพ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fishList as $fish)
                                    <tr>
                                        <td>{{ $fish->FishID }}</td>
                                        <td>{{ $fish->FishName }}</td>
                                        <td>{{ $fish->FishRarity }}</td>
                                        <td>{{ $fish->FishWorth }}</td>
                                        <td>{{ $fish->FishTokenWorth }}</td>
                                        <td>
                                            @if($fish->FishImage)
                                                <img src="{{ asset('storage/' . $fish->FishImage) }}" alt="{{ $fish->FishName }}" width="100">
                                            @else
                                                No Image
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.edit-fish', $fish->FishID) }}" class="btn btn-warning btn-sm">แก้ไขปลา</a>
                                            <form action="{{ route('admin.delete-fish', $fish->FishID) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">ลบปลา</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $fishList->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
    <effect></effect>
</body>

<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script>
        new Vue({
            el: '#app',
            props: {
    user: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      localUser: this.user
    };
  },
  watch: {
    user(newUser) {
      this.localUser = newUser;
    }
  },
            data: {
                localUser: {!! json_encode(Auth::user()) !!}
            },
            methods: {
                navigateTo(url) {
                    window.location.href = url;
                },
                logout() {
                    document.getElementById('logout-form').submit();
                },
                async logout() {
      const toast = useToast();
      try {
        await axios.post('/logout');
        localStorage.removeItem('user');
        this.localUser = null;
        toast.success('Logged out successfully!');
        window.location.href = '/login';
      } catch (error) {
        toast.error('Failed to log out.');
      }
    },
    navigateTo(path) {
      window.location.href = path;
    }
            }
        });
    </script>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
</html>