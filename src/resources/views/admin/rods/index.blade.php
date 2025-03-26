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
                <div class="card-header">Rod List</div>

                <div class="card-body">
                    <a href="{{ route('admin.rods.create') }}" class="btn btn-primary mb-3">Add New Rod</a>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Chance Common</th>
                                <th>Chance Rare</th>
                                <th>Chance SR</th>
                                <th>Chance SSR</th>
                                <th>Chance NFT</th>
                                <th>Chance Special</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rods as $rod)
                                <tr>
                                    <td>{{ $rod->image }}</td>
                                    <td>{{ $rod->name }}</td>
                                    <td>{{ $rod->price }}</td>
                                    <td>{{ $rod->chance_common }}</td>
                                    <td>{{ $rod->chance_rare }}</td>
                                    <td>{{ $rod->chance_sr }}</td>
                                    <td>{{ $rod->chance_ssr }}</td>
                                    <td>{{ $rod->chance_nft }}</td>
                                    <td>{{ $rod->chance_special }}</td>
                                    <td>
                                        <a href="{{ route('admin.rods.edit', $rod->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('admin.rods.destroy', $rod->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $rods->links() }}
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