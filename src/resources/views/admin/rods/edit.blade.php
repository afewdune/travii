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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Rod</div>

                <div class="card-body">
                    <form action="{{ route('admin.rods.update', $rod->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $rod->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" value="{{ $rod->price }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="chance_common" class="form-label">Chance Common</label>
                            <input type="number" step="0.01" class="form-control" id="chance_common" name="chance_common" value="{{ $rod->chance_common }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="chance_rare" class="form-label">Chance Rare</label>
                            <input type="number" step="0.01" class="form-control" id="chance_rare" name="chance_rare" value="{{ $rod->chance_rare }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="chance_sr" class="form-label">Chance SR</label>
                            <input type="number" step="0.01" class="form-control" id="chance_sr" name="chance_sr" value="{{ $rod->chance_sr }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="chance_ssr" class="form-label">Chance SSR</label>
                            <input type="number" step="0.01" class="form-control" id="chance_ssr" name="chance_ssr" value="{{ $rod->chance_ssr }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="chance_nft" class="form-label">Chance NFT</label>
                            <input type="number" step="0.01" class="form-control" id="chance_nft" name="chance_nft" value="{{ $rod->chance_nft }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="chance_special" class="form-label">Chance Special</label>
                            <input type="number" step="0.01" class="form-control" id="chance_special" name="chance_special" value="{{ $rod->chance_special }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            @if($rod->image)
                                <img src="{{ asset('storage/' . $rod->image) }}" alt="{{ $rod->name }}" width="100">
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Update Rod</button>
                    </form>
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