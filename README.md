# KTÜ EHK — WordPress teması ve içerik eklentisi

KTÜ Elektronik ve Haberleşme Kulübü (ktuehk.com.tr) için yeniden tasarlanmış WordPress altyapısı. Bu depoda iki parça var:

| Klasör | Ne işe yarar |
| --- | --- |
| `wp-content/themes/ktuehk` | **KTÜ EHK teması**: tüm görünüm (ana sayfa, yazılar, projeler, etkinlikler, Hakkımızda, arama, 404). |
| `wp-content/plugins/ktuehk-core` | **KTÜ EHK Çekirdek eklentisi**: Projeler ve Etkinlikler içerik türleri, alanları, filtreleri, SEO/schema çıktısı. |

İçerik modeli bilerek eklentide tutuldu: tema ileride değiştirilse bile projeler ve etkinlikler kaybolmaz.

![Ana sayfa](docs/screenshots/ana-sayfa.jpg)

<details>
<summary>Diğer ekran görüntüleri</summary>

| Yazı detayı | Proje detayı |
| --- | --- |
| ![Yazı detayı](docs/screenshots/yazi-detay.jpg) | ![Proje detayı](docs/screenshots/proje-detay.jpg) |

| Etkinlikler | Mobil |
| --- | --- |
| ![Etkinlikler](docs/screenshots/etkinlikler.jpg) | ![Mobil](docs/screenshots/mobil.jpg) |

Görüntüler test verisiyle alınmıştır.
</details>

---

## Önemli: Mevcut site hakkında

Geliştirme ortamından `ktuehk.com.tr` adresine erişilemedi (ağ politikası engeli), bu yüzden canlı sitenin teması, eklentileri ve içerik yapısı doğrudan incelenemedi. Bu nedenle sistem **mevcut yapıyı bozmayacak şekilde savunmacı** tasarlandı:

- Hiçbir içerik, ayar veya URL silinmez/değiştirilmez. Eklentinin silme (uninstall) rutini yoktur.
- Kalıcı bağlantı (permalink) yapısına dokunulmaz. Yazı ve sayfa adresleri aynen kalır.
- Sitede zaten bir **proje/etkinlik içerik türü** varsa yeniden oluşturulmaz; **Ayarlar › KTÜ EHK** ekranından anahtarı ve URL ön eki girilerek mevcut tür benimsenir (aşağıya bakın).
- Bir **SEO eklentisi** (Yoast, Rank Math, AIOSEO, SEOPress, The SEO Framework, Slim SEO) varsa meta etiketleri ve şema ona bırakılır; tema yalnızca SEO eklentilerinin üretmediği Event şemasını ekler.
- Eski temanın **logosu ve sosyal medya bağlantıları** tema değiştirildiğinde otomatik taşınır.
- **İletişim sayfasının içeriğine dokunulmaz**; yalnızca menüde/alt bilgide bağlantısı yer alır. İletişim bilgileri alt bilgide tekrarlanmaz veya öne çıkarılmaz.

Canlıya almadan önce aşağıdaki kontrol listesini bir **hazırlık (staging) kopyasında** uygulamanız önerilir.

---

## Kurulum

### 0. Yedek alın
Hosting panelinizden veya bir yedekleme eklentisiyle **dosya + veritabanı yedeği** alın. Mümkünse önce bir staging kopyasında deneyin.

### 1. Dosyaları yükleyin
İki yoldan biri:

- **Yönetim panelinden (önerilen):** Ubuntu'da depo klasöründe `./bin/paketle.sh` çalıştırın. `dist/ktuehk-core.zip` dosyasını *Eklentiler › Yeni ekle › Eklenti yükle*, `dist/ktuehk.zip` dosyasını *Görünüm › Temalar › Yeni ekle › Tema yükle* ile yükleyin.
- **FTP/SFTP ile:** `wp-content/themes/ktuehk` ve `wp-content/plugins/ktuehk-core` klasörlerini sunucudaki aynı yerlere kopyalayın.

### 2. Önce eklentiyi, sonra temayı etkinleştirin
1. *Eklentiler* › **KTÜ EHK Çekirdek** › Etkinleştir. (Varsayılan program, alan ve etkinlik türü terimleri bir kez oluşturulur.)
2. *Görünüm › Temalar* › **KTÜ EHK** › Etkinleştir.

Sıra ters olsa da sorun çıkmaz; tema, eklenti yoksa bir uyarı ve "Eklentiyi etkinleştir" bağlantısı gösterir.

### 3. Önerilen kurulumu tamamlayın
Tema etkinleştirildiğinde panelde **"KTÜ EHK teması — önerilen kurulum"** kutusu çıkar. Yapılacak adımları listeler ve yalnızca eksik olanları tamamlar:

- Ana sayfa "son yazılar" gösteriyorsa statik bir *Ana Sayfa* sayfası atanır (adres değişmez).
- Yazı listesi sayfası yoksa *Yazılar* sayfası (`/yazilar/`) oluşturulup atanır; `yazilar` adlı bir sayfa zaten varsa o kullanılır.
- *Hakkımızda* sayfası yoksa, düzenlenebilir hazır içerikle **taslak** olarak oluşturulur. İnceleyip yayımlayın.

### 4. Kontrol edin
- *Ayarlar › Genel › Site Dili*: **Türkçe** olmalı (tarih/ay adları ve büyük harf dönüşümleri için; ör. "BİZ KİMİZ?").
- *Ayarlar › Kalıcı bağlantılar*: değiştirmeden bir kez **Kaydet**'e basın (yeni URL'ler için kuralları yeniler).
- *Görünüm › Menüler*: Menü atanmamışsa tema otomatik olarak *Ana Sayfa, Yazılar, Projeler, Etkinlikler, Hakkımızda* (+ varsa mevcut İletişim sayfası) menüsünü gösterir. Kendi menünüzü "Ana menü (üst)" ve "Alt bilgi — Hızlı bağlantılar" konumlarına atayabilirsiniz.
- *Görünüm › Özelleştir › KTÜ EHK Tema Ayarları*: menü rengi, koyu zemin logosu, ana sayfa metinleri, bölüm açıklamaları ve sosyal medya bağlantıları.

---

## Geçiş kontrol listesi (mevcut site için)

| Durum | Ne yapmalı |
| --- | --- |
| Eski temada/eklentide bir **proje veya etkinlik içerik türü** vardı | *Ayarlar › KTÜ EHK* › "Tanılama" tablosuna bakın. "Kayıtlı değil" görünen veya başka eklentiye ait türün **anahtarını** (ör. `project`) ve **mevcut URL ön ekini** (ör. `portfolio`) girip kaydedin. İçerik taşınmaz; aynı kayıtlar yeni tasarımla ve aynı adreslerle görünür. |
| Projeler normal **yazı** olarak bir "Projeler" kategorisinde tutuluyordu | Hiçbir şey bozulmaz; kategori sayfası yeni tasarımla çalışır. Projeleri yeni yapıya almak isterseniz *Post Type Switcher* gibi bir eklentiyle türünü değiştirebilirsiniz (eski adresler WordPress tarafından yeni adrese yönlendirilir). |
| Bir **SEO eklentisi** kullanılıyor | Ek işlem gerekmez; meta ve şema o eklentide kalır. |
| Sayfalar bir **sayfa oluşturucu** (Elementor vb.) ile yapılmış | İçerik korunur. Geniş düzen gerekiyorsa sayfa ayarlarından **"Tam genişlik"** şablonunu seçin. |
| Eski temanın **widget**'ları vardı | Bu temada widget alanı yoktur; widget'lar silinmez, *Görünüm › Widget'lar* altında "Etkin olmayan" olarak saklanır. |
| **Logo** | Eski temadaki logo otomatik taşınır. Mavi menüde ve koyu alt bilgide logo beyaz bir kutucukta gösterilir; beyaz versiyonunuz varsa *Özelleştir › Üst menü › Koyu zemin logosu* alanına yükleyin veya menü rengini "Beyaz" yapın. |
| **Sosyal medya** | Eski tema ayarlarında bulunan Instagram, LinkedIn, GitHub, YouTube, X, Discord, Telegram, Medium bağlantıları otomatik taşınır. Eksik olanları *Özelleştir › Sosyal medya*'dan ekleyin. |
| **İletişim** | İletişim sayfası olduğu gibi kalır (`iletisim`, `bize-ulasin` veya `contact` adresli sayfa otomatik bağlanır). |

---

## İçerik yönetimi

### Yeni yazı (Yazılar)
- **Kategori** seçin (Elektronik, Haberleşme, RF ve Anten, Gömülü Sistemler…). Kategoriler yazılar sayfasında filtre olarak görünür.
- **Öne çıkan görsel** ekleyin (kartlarda 16:9 kırpılır, yazı sayfasında kapak olur). Görselin *Alternatif metin* alanını doldurun.
- **Özet** alanı kartlarda ve yazı başlığının altında giriş metni olarak kullanılır; arama motoru açıklaması da buradan gelir.
- Yazıyı bir öğrenci adına yayımlıyorsanız sağdaki **Yazar Bilgisi** kutusuna adını ve kısa bilgisini girin.
- En az üç ara başlık (H2/H3) olan yazılarda **"Bu yazıda" içindekiler** listesi otomatik oluşur.
- **Formüller (LaTeX):** satır içi `\( ... \)`, ayrı satırda `$$ ... $$` veya `\[ ... \]`. KaTeX yalnızca formül içeren sayfalarda yüklenir.
- **Kod:** "Kod" bloğunu kullanın. Dil etiketi için bloğun *Gelişmiş › Ek CSS sınıfları* alanına `language-python`, `language-c` vb. yazın. Her kod bloğunda "Kopyala" düğmesi bulunur.
- **Not / Uyarı kutuları** ve **Teknik tablo**: Grup veya Tablo bloğunun *Stiller* bölümünden ya da blok ekleyicideki *Desenler › KTÜ EHK* kategorisinden ekleyin.
- Okuma süresi kelime sayısından otomatik hesaplanır.

### Yeni proje (Projeler)
- **Başlık**, **Özet** (kısa açıklama) ve **Proje kapak görseli**.
- Sağ panel: **Yarışma / Program** (TÜBİTAK 2209-A, 2209-B, TEKNOFEST, Kulüp İçi…) ve **Alanlar**. Yeni program/alan eklemek için *Projeler › Yarışma / Program* ve *Projeler › Alanlar*.
- **Proje Bilgileri** kutusu: Yıl, Durum (Planlanıyor / Devam ediyor / Tamamlandı), *Ana sayfada öne çıkar*, Başarı/Ödül, Danışman, Ekip (her satıra `Ad Soyad — Rol`), GitHub / Dokümantasyon / Demo bağlantıları.
- **Proje Detayları** kutusu: Problem, Amaç, Kullanılan teknolojiler (virgülle), Donanım ve Yazılım (her satıra bir öğe), Süreç, Sonuçlar. Boş bırakılan bölüm sayfada görünmez.
- Ana içerik editörü "Proje detayları" bölümü olarak gösterilir: şema, devre görselleri, ölçüm sonuçları için kullanın.
- **Proje Galerisi**: "Galeriyi düzenle" ile birden çok görsel seçin; sitede tıklayınca büyüyen galeri olarak görünür.
- Projeler sayfasında **yıl, program, alan ve durum** filtreleri otomatik oluşur.

### Yeni etkinlik (Etkinlikler)
- **Başlık**, **Özet**, **Etkinlik görseli** (kartta kullanılır) ve sağ panelden **Etkinlik türü**.
- **Etkinlik Bilgileri**: Başlangıç, Bitiş (isteğe bağlı), Konum, Harita bağlantısı, Çevrim içi, Kayıt bağlantısı, Konuşmacılar.
- **Afiş ve Fotoğraflar**: afiş kırpılmadan gösterilir; etkinlik sonrası fotoğrafları galeriye ekleyin.
- Etkinlik, bitiş saatine kadar (bitiş yoksa başladığı günün sonuna kadar) **Yaklaşan** olarak listelenir, sonra otomatik olarak **Geçmiş** etkinliklere geçer. Yaklaşan etkinlikte "Kayıt ol" ve "Takvime ekle" (.ics) düğmeleri görünür.

### Hakkımızda sayfası
Metinler normal sayfa içeriğidir ve editörden değiştirilebilir. Dinamik bölümler kısa kodlarla eklenir ve istenen yere taşınabilir:

| Kısa kod | Çıktı |
| --- | --- |
| `[ehk_calisma_alanlari]` | Çalışma alanları kartları |
| `[ehk_imkanlar]` | Öğrencilere sunulan imkânlar |
| `[ehk_programlar]` | Yarışma/programlar ve proje sayıları |

`hakkimizda` adresli sayfa bu düzeni otomatik kullanır; başka bir sayfa için *Sayfa › Şablon › Hakkımızda* seçin.

---

## Tasarım sistemi

- **Renkler:** KTÜ mavisi (`#0b4a96`, menü `#082a5c`), lacivert başlıklar (`#0a1f3d`), beyaz içerik, açık gri bölüm zemini (`#f4f6f9`). Mavi yalnızca menü, eylem düğmeleri, bağlantılar ve vurgularda kullanılır.
- **Yazı tipleri:** IBM Plex Sans (değişken font, Türkçe karakter desteği) ve teknik etiketler/kod için IBM Plex Mono. Sunucudan yüklenir; Google Fonts'a istek yapılmaz.
- **İkonlar:** Lucide (ISC) ve marka ikonları için Simple Icons (CC0), satır içi SVG olarak.
- **Dekor:** çok soluk teknik ızgara, PCB izleri ve modüle edilmiş (AM) sinyal görseli; animasyon, slider, pop-up, gradyan veya cam efekti yoktur.
- **Kırılım noktaları:** 640 px, 768 px, 1024 px (masaüstü menü), 1200 px (içindekiler kenar sütunu).

Tüm renk/ölçü değişkenleri `assets/css/main.css` başındaki `:root` bloğundadır.

## Performans ve erişilebilirlik

- Sayfa başına tek CSS (~14 KB gzip) ve tek küçük, ertelenmiş JS dosyası; derleme adımı yoktur.
- Kartlarda tembel yükleme (`loading="lazy"`), kapak görsellerinde `fetchpriority="high"`, sabit en-boy oranlarıyla düzen kayması (CLS) önlenir. Mevcut görseller için yeni boyut üretmek gerekmez (WordPress'in standart boyutları kullanılır).
- Emoji betiği kaldırıldı; yazar avatarları için varsayılan olarak baş harfler kullanılır (Gravatar'a harici istek yok). Gravatar istenirse: `add_filter( 'ktuehk_use_gravatar', '__return_true' );`
- "İçeriğe geç" bağlantısı, görünür odak halkaları, klavyeyle kullanılabilen menü/arama/galeri (Esc ile kapanır), `prefers-reduced-motion` desteği. JavaScript kapalıyken de menü erişilebilir.
- Test ortamında 10 sayfa tipi, masaüstü ve mobilde **axe-core** denetiminden ihlalsiz geçti.

## SEO

- Tek `<h1>`, anlamsal HTML, kırıntı (breadcrumb) navigasyonu.
- SEO eklentisi yoksa: meta açıklama, Open Graph/Twitter, arşivlerde canonical ve JSON-LD (`Organization`, `WebSite` + arama, `BreadcrumbList`, `Article`, `Event`, projeler için `CreativeWork`).
- Filtreli liste sayfaları (`?yil=`, `?durum=` vb.) `noindex, follow` alır; arama sonuçları WordPress tarafından zaten `noindex`'tir.
- Görsellerde alternatif metin yoksa içerik başlığı kullanılır.

---

## Geliştirici notları

```
wp-content/
├── plugins/ktuehk-core/
│   ├── ktuehk-core.php          # Önyükleme, etkinleştirme, sürüm yükseltme
│   └── includes/
│       ├── settings.php         # Ayarlar › KTÜ EHK (tür anahtarı/URL + tanılama)
│       ├── content-types.php    # Proje/Etkinlik türleri ve taksonomiler
│       ├── fields.php           # Meta alanları, meta kutuları, kayıt
│       ├── helpers.php          # Temanın kullandığı genel API
│       ├── query.php            # Arşiv filtreleri, noindex
│       ├── seo.php              # Meta, Open Graph, JSON-LD
│       ├── ics.php              # "Takvime ekle"
│       └── admin.php            # Liste sütunları, filtreler, pano
└── themes/ktuehk/
    ├── inc/                     # Kurulum, varlıklar, şablon yardımcıları, Özelleştirici
    ├── template-parts/          # Kartlar, sayfa başlığı, bölümler, galeri
    ├── page-templates/          # Hakkımızda, Tam genişlik
    └── assets/                  # CSS, JS, fontlar, KaTeX
```

- Alanlar `_ehk_` önekli normal post meta olarak saklanır ve REST API'de açıktır (`show_in_rest`). Ek alan eklentisi (ACF vb.) gerekmez.
- Değiştirilebilir listeler için filtreler: `ktuehk_focus_areas`, `ktuehk_offerings`, `ktuehk_pillars`, `ktuehk_default_menu_items`, `ktuehk_project_statuses`, `ktuehk_projects_per_page`, `ktuehk_events_per_page`, `ktuehk_core_field_groups`, `ktuehk_core_schema_graph`, `ktuehk_seo_plugin_active`.
- Proje filtresi parametreleri: `?program=`, `?alan=`, `?yil=`, `?durum=`; etkinlik: `?tur=`, `?donem=yaklasan|gecmis`.
- Yerel deneme için herhangi bir WordPress kurulumunda iki klasörü `wp-content` altına kopyalamanız yeterlidir. PHP 7.4+ ve WordPress 6.2+ gerekir.

### Nasıl test edildi
WordPress 6.7 + SQLite üzerinde, Türkçe örnek içerikle (yazılar, projeler, yaklaşan/geçmiş etkinlikler):
- Tüm PHP dosyaları için `php -l`; 40'tan fazla URL'de (arşivler, filtreler, aramalar, 404, besleme, site haritası) `WP_DEBUG` açıkken uyarı/hata olmadığı doğrulandı.
- Playwright ile 1440 / 820 / 390 px ekran görüntüleri ve yatay taşma kontrolü.
- Blok editöründe yeni proje oluşturma ve meta kayıt (ters eğik çizgili LaTeX dahil), Hakkımızda içeriğinin geçerli bloklar olarak açılması.
- Eklentinin tema etkinken panelden etkinleştirilmesi, eklenti kapalıyken temanın çalışması, mevcut bir `project` türünün benimsenmesi, SEO eklentisi varken çift çıktı olmaması, eski tema logosu/sosyal bağlantılarının taşınması.

## Lisanslar
- Tema ve eklenti: GPL-2.0-or-later
- IBM Plex Sans / Mono: SIL Open Font License 1.1 (`assets/fonts/OFL.txt`)
- KaTeX: MIT (`assets/vendor/katex/LICENSE`)
- Lucide ikonları: ISC · Simple Icons: CC0-1.0
