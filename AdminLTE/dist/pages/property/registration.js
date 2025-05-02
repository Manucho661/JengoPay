// delete building

function handleDelete(event, id, type) {
  event.stopPropagation(); // Prevents event bubbling

  if (confirm("Are you sure?")) {
    fetch('../actions/delete_record.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'id=' + encodeURIComponent(id) + '&type=' + encodeURIComponent(type)
    })
    .then(res => res.text())
    .then(data => {
      alert(data);
      location.reload();
    })
    .catch(err => console.error('Delete error:', err));
  }
}




// fetch county , constituency & ward

const data = {
  "Mombasa": {
  "Changamwe": ["Port Reitz", "Kipevu", "Airport", "Changamwe", "Chaani"],
  "Jomvu": ["Jomvu Kuu", "Miritini", "Mikindani"],
  "Kisauni": ["Mjambere", "Junda", "Bamburi", "Mwakirunge", "Mtopanga", "Magogoni"],
  "Nyali": ["Frere Town", "Ziwa la Ngombe", "Mkomani", "Kongowea", "Kadzandani"],
  "Likoni": ["Mtongwe", "Shika Adabu", "Bofu", "Likoni", "Timbwani"],
  "Mvita": ["Ganjoni", "Majengo", "Tudor", "Tononoka", "Shimanzi/Ganjoni"]
  },
  "Kwale": {
  "Msambweni": ["Gombato Bongwe", "Ukunda", "Kinondo", "Ramisi"],
  "Lunga Lunga": ["Dzombo", "Pongwe/Kikoneni", "Mwereni", "Vanga"],
  "Matuga": ["Tsimba Golini", "Waa", "Tiwi", "Kubo South", "Mkongani"],
  "Kinango": ["Ndavaya", "Puma", "Kinango", "Chengoni/Samburu", "Mackinnon Road", "Mwavumbo"]
  },
  "Kilifi": {
  "Kilifi North": ["Tezo", "Sokoni", "Kibarani", "Dabaso", "Matsangoni", "Watamu", "Mnarani"],
  "Kilifi South": ["Junju", "Mwarakaya", "Shimo la Tewa", "Chasimba", "Mtepeni", "Madzimboni"],
  "Kaloleni": ["Kayafungo", "Kaloleni", "Mwanamwinga", "Rabai/Kambe"],
  "Rabai": ["Ruruma", "Rabai/Kisurutini", "Mwawesa", "Ruruma"],
  "Ganze": ["Sokoke", "Bamba", "Jaribuni", "Ganze"],
  "Malindi": ["Ganda", "Malindi Town", "Shella", "Jilore", "Kakuyuni"],
  "Magarini": ["Marafa", "Magarini", "Gongoni", "Adu", "Garashi", "Sabaki"]
  },
  "Tana River": {
  "Bura": ["Chewele", "Hirimani", "Bangale", "Madogo", "Bura"],
  "Galole": ["Kinakomba", "Mikinduni", "Chewani", "Wayu"],
  "Garsen": ["Garsen Central", "Garsen South", "Garsen North", "Kipini East", "Kipini West"]
  },
  "Lamu": {
  "Lamu East": ["Faza", "Kiunga", "Basuba"],
  "Lamu West": ["Shella", "Mkomani", "Hindi", "Mkunumbi", "Hongwe", "Bahari", "Witu"]
  },
  "Taita Taveta": {
  "Taveta": ["Chala", "Mahoo", "Bomani", "Mboghoni"],
  "Wundanyi": ["Wumingu/Kishushe", "Mwanda/Mgange", "Wundanyi/Mbale", "Werugha"],
  "Mwatate": ["Ronge", "Mwatate", "Bura", "Chawia"],
  "Voi": ["Mbololo", "Ngolia", "Kasigau", "Sagalla"]
  },
  "Garissa": {
  "Garissa Township": ["Waberi", "Galbet", "Township", "Iftin"],
  "Balambala": ["Balambala", "Saka", "Sankuri"],
  "Lagdera": ["Modogashe", "Benane", "Goreale", "Maalimin", "Sabena", "Baraki"],
  "Dadaab": ["Dadaab", "Labasigale", "Damajale", "Liboi", "Abakaile"],
  "Fafi": ["Bura", "Dekaharia", "Jarajila", "Nanighi", "Fafi"],
  "Ijara": ["Masalani", "Sangailu", "Ijara", "Hulugho"]
  },
  "Wajir": {
  "Wajir North": ["Gurar", "Bute", "Korondile", "Malkagufu", "Batalu", "Danaba"],
  "Wajir East": ["Wagberi", "Township", "Barwago", "Khorof/Harar"],
  "Tarbaj": ["Elben", "Sarman", "Tarbaj", "Wargadud"],
  "Wajir West": ["Arbajahan", "Hadado/Athibohol", "Ademasajide", "Ganyure/Wagalla"],
  "Eldas": ["Elnur/Tula Tula", "Della", "Lakoley South/Basir", "Eldas"],
  "Wajir South": ["Benane", "Habasswein", "Lagboghol South", "Burder", "Dadaja Bulla", "Ibrahim Ure"]
  },
  "Mandera": {
  "Mandera West": ["Takaba South", "Takaba", "Lagsure", "Gither"],
  "Banissa": ["Banissa", "Derkhale", "Guba", "Malkamari", "Malkamari"],
  "Mandera North": ["Ashabito", "Guticha", "Rhamu", "Rhamu Dimtu", "Morothile"],
  "Mandera South": ["Wargadud", "Kutulo", "Elwak South", "Elwak North", "Shimbir Fatuma"],
  "Mandera East": ["Neboi", "Township", "Khalalio", "Libehia", "Sala"],
  "Lafey": ["Fino", "Lafey", "Waranqara", "Alango Gof", "Sala"]
  },
  "Marsabit": {
  "Moyale": ["Butiye", "Sololo", "Heillu/Manyatta", "Uran", "Obbu"],
  "North Horr": ["Dukana", "Maikona", "Turbi", "Illeret"],
  "Saku": ["Sagam", "Karare", "Marsabit Central"],
  "Laisamis": ["Laisamis", "Korr/Ngurunit", "Logologo", "Loiyangalani"]
  },
  "Isiolo": {
  "Isiolo North": ["Wabera", "Bulla Pesa", "Chari", "Cherab", "Ngare Mara", "Burat", "Oldonyiro"],
  "Isiolo South": ["Garbatulla", "Kinna", "Sericho"]
  },
  "Tharaka-Nithi": {
  "Chuka/Igambang'ombe": ["Chuka", "Igambang'ombe", "Karingani", "Magumoni"],
  "Maara": ["Mitheru", "Muthambi", "Mwimbi", "Ganga"],
  "Tharaka": ["Chiakariga", "Marimanti", "Mukothima", "Gatunga"]
  },
  "Embu": {
  "Manyatta": ["Kithimu", "Ngandori East", "Ngandori West", "Central Ward"],
  "Runyenjes": ["Kyeni North", "Kyeni South", "Gaturi South", "Gaturi North"],
  "Mbeere North": ["Muminji", "Nthawa", "Mavuria"],
  "Mbeere South": ["Makima", "Mwea", "Kiambere"]
  },
  "Meru": {
  "Igembe South": ["Athiru Gaiti", "Akachiu", "Kanuni", "Maua", "Kiegoi/Antubochiu"],
  "Igembe Central": ["Akirang'ondu", "Athiru", "Antuambui", "Njia", "Kangeta"],
  "Igembe North": ["Ntunene", "Antuanga", "Antubetwe Kiongo", "Naathu", "Amwathi"],
  "Tigania West": ["Athwana", "Akithii", "Kianjai", "Nkomo", "Mbeu"],
  "Tigania East": ["Thangatha", "Mikinduri", "Karama", "Muthara", "Kiguchwa"],
  "North Imenti": ["Municipality", "Ntima East", "Ntima West", "Nyaki East", "Nyaki West"],
  "Buuri": ["Timau", "Kisima", "Kiirua/Naari", "Ruiri/Rwarera", "Kibirichia"],
  "Central Imenti": ["Mwanganthia", "Abothuguchi Central", "Abothuguchi West", "Kiagu"],
  "South Imenti": ["Mitunguu", "Igoji East", "Igoji West", "Abogeta East", "Abogeta West", "Nkuene"]
  },
  "Kitui": {
  "Kitui Central": ["Miambani", "Township", "Kyangwithya East", "Kyangwithya West"],
  "Kitui East": ["Nzambani", "Chuluni", "Mutito", "Endau/Malalani"],
  "Kitui South": ["Ikanga/Kyatune", "Mutomo", "Mutha", "Ikutha"],
  "Kitui West": ["Mutonguni", "Matinyani", "Kisasi", "Kwa Mutonga/Kithumula"],
  "Mwingi Central": ["Central", "Kivou", "Nguni", "Nuu"],
  "Mwingi North": ["Kyuso", "Ngomeni", "Tharaka", "Mumoni"],
  "Mwingi West": ["Migwani", "Kiomo/Kyethani", "Nzeluni", "Waita"]
  },
  "Kiambu": {
  "Gatundu North": ["Gituamba", "Githobokoni", "Chania", "Mang'u"],
  "Gatundu South": ["Kiamwangi", "Kiganjo", "Ndarugo", "Ngenda"],
  "Githunguri": ["Githunguri", "Githiga", "Ikinu", "Ngewa", "Komothai"],
  "Juja": ["Murera", "Theta", "Juja", "Witeithie", "Kalimoni"],
  "Kabete": ["Gitaru", "Muguga", "Nyathuna", "Kabete", "Uthiru"],
  "Kiambaa": ["Cianda", "Karuri", "Ndenderu", "Muchatha", "Kihara"],
  "Kiambu Town": ["Ting'ang'a", "Ndumberi", "Riabai", "Township"],
  "Kikuyu": ["Karai", "Nachu", "Sigona", "Kikuyu", "Kinoo"],
  "Limuru": ["Bibirioni", "Limuru Central", "Ndeiya", "Limuru East", "Ngecha/Tigoni"],
  "Lari": ["Kinale", "Kijabe", "Nyanduma", "Kamburu", "Lari/Kirenga"],
  "Ruiru": ["Gitothua", "Biashara", "Gatongora", "Kahawa/Sukari", "Kahawa Wendani", "Kiuu", "Mwiki", "Mwihoko"],
  "Thika Town": ["Township", "Kamenu", "Hospital", "Gatuanyaga", "Ngoliba"]
  },
  "Machakos": {
  "Machakos Town": ["Kalama", "Mua", "Mutituni", "Mumbuni North"],
  "Mwala": ["Masii", "Muthetheni", "Wamunyu", "Kibauni"],
  "Kangundo": ["Kangundo North", "Kangundo Central", "Kangundo East", "Kangundo South"],
  "Matungulu": ["Matungulu North", "Matungulu East", "Matungulu West", "Tala"],
  "Kathiani": ["Mitaboni", "Kathiani Central", "Upper Kaewa/Iveti", "Lower Kaewa/Kaani"],
  "Mavoko": ["Athi River", "Kinanie", "Mlolongo", "Syokimau/Mulolongo"],
  "Yatta": ["Ndalani", "Matuu", "Kithimani", "Katangi"]
  },
  "Makueni": {
  "Makueni": ["Wote", "Mavindini", "Kathonzweni", "Mbitini"],
  "Kibwezi East": ["Masongaleni", "Mtito Andei", "Thange", "Ivingoni/Nzambani"],
  "Kibwezi West": ["Makindu", "Nguumo", "Nguu/Masumba", "Kikumbulyu North", "Kikumbulyu South"],
  "Kaiti": ["Kilungu", "Ilima", "Ukia", "Kee"],
  "Kilome": ["Mukaa", "Kasikeu", "Kiima Kiu/Kalanzoni"],
  "Mbooni": ["Tulimani", "Mbooni", "Kithungo/Kitundu", "Kisau/Kithuki"]
  },
  "Nyandarua": {
  "Ol Kalou": ["Githioro", "Karau", "Mirangine", "Rurii"],
  "Ol Jorok": ["Gathanji", "Gatimu", "Weru", "Charagita"],
  "Kinangop": ["Engineer", "Githabai", "North Kinangop", "Murungaru"],
  "Kipipiri": ["Wanjohi", "Kipipiri", "Geta", "Githioro"],
  "Ndaragwa": ["Leshau Pondo", "Kiriita", "Central", "Shamata"]
  },
  "Nyeri": {
  "Tetu": ["Dedan Kimathi", "Aguthi-Gaaki", "Wamagana"],
  "Kieni": ["Mweiga", "Naromoru/Kiamathaga", "Gatarakwa", "Thegu River"],
  "Mathira": ["Karatina Town", "Ruguru", "Iriaini", "Konyu"],
  "Othaya": ["Chinga", "Iriaini", "Karima", "Mahiga"],
  "Mukurweini": ["Gikondi", "Rugi", "Mukurweini Central", "Mukure"],
  "Nyeri Town": ["Rware", "Kamakwa/Mukaro", "Kiganjo/Mathari", "Gatitu/Muruguru"]
  },
  "Kirinyaga": {
  "Mwea": ["Mutithi", "Tebere", "Wamumu", "Thiba"],
  "Gichugu": ["Kabare", "Ngariama", "Karumandi", "Njukiini"],
  "Ndia": ["Mukure", "Kiine", "Kariti", "Baragwi"],
  "Kirinyaga Central": ["Inoi", "Mutira", "Kerugoya", "Kanyekini"]
  },
  "Murang'a": {
  "Kangema": ["Kanyenyaini", "Muguru", "Rwathia"],
  "Mathioya": ["Gitugi", "Kiru", "Kamacharia"],
  "Kiharu": ["Mugoiri", "Mbiri", "Township", "Kimathi"],
  "Kigumo": ["Kangari", "Kinyona", "Kagunduini", "Kagaa"],
  "Maragua": ["Ichagaki", "Kimorori/Wempa", "Makuyu", "Kambiti"],
  "Kandara": ["Gaichanjiru", "Ng'araria", "Muruka", "Ithiru"],
  "Gatanga": ["Kakuzi/Mitubiri", "Kihumbuini", "Kariara", "Kang'ari"]
  },
  "Kiambu": {
  "Gatundu South": ["Kiamwangi", "Kiganjo", "Ng'enda", "Ndarugu"],
  "Gatundu North": ["Gituamba", "Mang'u", "Chania", "Kahuguini"],
  "Juja": ["Theta", "Witeithie", "Murera", "Juja", "Kalimoni"],
  "Thika Town": ["Township", "Kamenu", "Hospital", "Gatuanyaga", "Ngoliba"],
  "Ruiru": ["Biashara", "Gitothua", "Gatongora", "Kahawa Sukari", "Kahawa Wendani", "Mwiki", "Mwihoko", "Kiuu"],
  "Githunguri": ["Githunguri", "Githiga", "Ikinu", "Komothai", "Komo"],
  "Kiambu": ["Ting'ang'a", "Ndumberi", "Riabai", "Kiambu Township"],
  "Kiambaa": ["Cianda", "Karuri", "Ndenderu", "Muchatha", "Kihara"],
  "Kikuyu": ["Kikuyu", "Sigona", "Karai", "Nachu", "Kinoo"],
  "Kabete": ["Uthiru", "Kabete", "Gitaru", "Muguga", "Nyathuna"],
  "Limuru": ["Limuru Central", "Limuru East", "Ngecha-Tigoni", "Biberioni", "Ndeiya"],
  "Lari": ["Kinale", "Kamburu", "Nyanduma", "Kijabe", "Lari"]
  },
  "Turkana": {
  "Turkana North": ["Kaeris", "Lapur", "Lake Zone", "Nakalale", "Kaaleng/Kaikor"],
  "Turkana West": ["Kakuma", "Lopur", "Songot", "Kalobeyei", "Lokichoggio"],
  "Turkana Central": ["Kanamkemer", "Kalokol", "Lodwar Township", "Kerio Delta", "Kotaruk/Lobei"],
  "Loima": ["Loima", "Lokiriama/Lorengippi", "Turkwel", "Atapar"],
  "Turkana South": ["Katilu", "Kaputir", "Lobokat", "Kalapata", "Lokichar"],
  "Turkana East": ["Lokori/Kochodin", "Katilia", "Kapedo/Napeitom"]
  },
  "West Pokot": {
  "Kapenguria": ["Kapenguria", "Riwo", "Mnagei", "Siyoi", "Endugh"],
  "Sigor": ["Sekerr", "Masool", "Lomut", "Weiwei"],
  "Kacheliba": ["Suam", "Kodich", "Kasei", "Kapchok", "Alale"],
  "Pokot South": ["Chepareria", "Batei", "Lelan", "Tapach"]
  },
  "Samburu": {
  "Samburu West": ["Lodokejek", "Maralal", "Loosuk", "Poro"],
  "Samburu North": ["El-Barta", "Nachola", "Ndoto", "Nyiro"],
  "Samburu East": ["Waso", "Wamba North", "Wamba West", "Wamba East"]
  },
  "Trans-Nzoia": {
  "Kwanza": ["Kapomboi", "Kwanza", "Keiyo", "Bidii"],
  "Endebess": ["Chepchoina", "Matumbei", "Endebess"],
  "Saboti": ["Matisi", "Tuwani", "Saboti", "Machewa", "Matisi"],
  "Kiminini": ["Waitaluk", "Kiminini", "Sirende", "Hospital", "Sikhendu"],
  "Cherangany": ["Makutano", "Kaplamai", "Motosiet", "Sinyerere", "Chepsiro/Kiptoror"]
  },
  "Uasin Gishu": {
  "Soy": ["Moi's Bridge", "Kapseret", "Kiplombe", "Kimumu", "Kuinet/Kapsuswa"],
  "Turbo": ["Turbo", "Ngenyilel", "Kamagut", "Kiplombe", "Kimumu"],
  "Moiben": ["Moiben", "Sergoit", "Karuna/Meibeki", "Tembelio", "Kimumu"],
  "Ainabkoi": ["Kapsoya", "Ainabkoi/Olare", "Kaptagat"],
  "Kapseret": ["Megun", "Langas", "Simat/Kapseret", "Kipkenyo", "Ngeria"],
  "Kesses": ["Racecourse", "Tulwet/Chuiyat", "Tarakwa", "Cheptiret/Kipchamo"]
  },
  "Elgeyo-Marakwet": {
  "Marakwet East": ["Endo", "Embobut/Embolot", "Sambirir", "Kapyego"],
  "Marakwet West": ["Lelan", "Sengwer", "Cherangany/Chebororwa", "Moiben/Kuserwo"],
  "Keiyo North": ["Emsoo", "Kamariny", "Kapchemutwa", "Tambach"],
  "Keiyo South": ["Kaptarakwa", "Chepkorio", "Soy North", "Soy South"]
  },
  "Nandi": {
  "Tinderet": ["Songhor/Soba", "Tindiret", "Chemelil/Chemase", "Kapsimotwa"],
  "Aldai": ["Kaptumo/Kaboi", "Kemeloi/Maraba", "Koyo/Ndurio", "Kabwareng", "Terik"],
  "Nandi Hills": ["Nandi Hills", "Chepkunyuk", "Kapchorwa", "O'lessos"],
  "Chesumei": ["Kiptuya", "Kosirai", "Lelmokwo/Ngechek", "Chemundu/Kapng'etuny", "Kaptel/Kamoiywo"],
  "Emgwen": ["Chepkumia", "Kapsabet", "Kilibwoni", "Kapkangani"],
  "Mosop": ["Kabiyet", "Kurgung/Surungai", "Chepterwai", "Sangalo/Kebulonik", "Kabisaga"]
  },
  "Baringo": {
  "Baringo Central": ["Kabarnet", "Sacho", "Tenges", "Kapropita", "Ewalel Chapchap"],
  "Baringo North": ["Barwesa", "Saimo Kipsaraman", "Saimo Soi", "Kabartonjo", "Bartabwa"],
  "Eldama Ravine": ["Lembus", "Lembus Kwen", "Ravine", "Maji Mazuri/Mumberes", "Lembus Perkerra", "Koibatek"],
  "Mogotio": ["Mogotio", "Emining", "Kisanana"],
  "Baringo South": ["Mukutani", "Marigat", "Ilchamus", "Mochongoi"],
  "Tiaty": ["Tirioko", "Kolowa", "Ribkwo", "Silale", "Tangulbei/Korossi", "Loiyamorok", "Churo/Amaya"]
  },
  "Laikipia": {
  "Laikipia East": ["Ngobit", "Tigithi", "Thingithu", "Nanyuki", "Umande"],
  "Laikipia West": ["Ol-Moran", "Rumuruti Township", "Githiga", "Marmanet", "Sosian", "Kinamba"],
  "Laikipia North": ["Segera", "Mugogodo West", "Mugogodo East"]
  },
  "Nakuru": {
  "Molo": ["Mariashoni", "Elburgon", "Turi", "Molo"],
  "Njoro": ["Mauche", "Kihingo", "Lare", "Nessuit", "Njoro"],
  "Naivasha": ["Biashara", "Hells Gate", "Lake View", "Mai Mahiu", "Maiella", "Naivasha East", "Olkaria", "Viwandani"],
  "Gilgil": ["Gilgil", "Elementaita", "Mbaruk/Eburu", "Murindati"],
  "Kuresoi South": ["Amalo", "Keringet", "Kiptagich", "Tinet"],
  "Kuresoi North": ["Kamara", "Kiptororo", "Nyota", "Sirikwa"],
  "Subukia": ["Kabazi", "Subukia", "Waseges"],
  "Rongai": ["Lemotit", "Menengai West", "Mosop", "Soin"],
  "Bahati": ["Bahati", "Dundori", "Kabatini", "Lanet/Umoja", "Kiamaina"],
  "Nakuru Town East": ["Biashara", "Flamingo", "Kivumbini", "Menengai", "Shabab"],
  "Nakuru Town West": ["Barut", "Kaptembwa", "London", "Rhoda", "Shaabab"]
  },
  "Narok": {
  "Kilgoris": ["Angata Barikoi", "Kilgoris Central", "Keyian", "Lolgorian", "Shankoe"],
  "Emurua Dikirr": ["Ilkerin", "Ololmasani", "Mogondo", "Kapsasian"],
  "Narok North": ["Olokurto", "Ololulung'a", "Nkareta", "Olorropil", "Narok Town"],
  "Narok East": ["Mosiro", "Ildamat", "Keekonyokie", "Suswa"],
  "Narok South": ["Majimoto/Naroosura", "Melelo", "Loita", "Sogoo", "Ololulunga"],
  "Narok West": ["Ilmotiok", "Mara", "Siana", "Naikarra"]
  },
  "Kajiado": {
  "Kajiado North": ["Ongata Rongai", "Nkaimurunya", "Olkeri", "Oloolua", "Ngong"],
  "Kajiado Central": ["Purko", "Ildamat", "Dalalekutuk", "Matapato North", "Matapato South"],
  "Kajiado East": ["Kaputiei North", "Kitengela", "Oloosirkon/Sholinke", "Kenyawa-Poka", "Imaroro"],
  "Kajiado West": ["Keekonyokie", "Iloodokilani", "Magadi", "Ewuaso Oonkidong'i", "Mosiro"],
  "Kajiado South": ["Entonet/Lenkisim", "Mbirikani/Eselenkei", "Kimana", "Rombo", "Kuku"]
  },
  "Kericho": {
  "Ainamoi": ["Ainamoi", "Kipchebor", "Kipchimchim", "Kipkelion", "Kipkelion East", "Kipkelion West"],
  "Belgut": ["Kabianga", "Waldai", "Chaik", "Kapsuser", "Cheptororiet/Seretut"],
  "Bureti": ["Cheboin", "Chemosot", "Kapkatet", "Kipreres", "Litein"],
  "Kipkelion East": ["Kedowa/Kimugul", "Londiani", "Chepseon", "Tendeno/Sorget"],
  "Kipkelion West": ["Kunyak", "Kamasian", "Kipkelion", "Chilchila"],
  "Sigowet/Soin": ["Sigowet", "Kaplelartet", "Soliat", "Soin"]
  },
  "Bomet": {
  "Bomet Central": ["Silibwet Township", "Ndaraweta", "Singorwet", "Chesoen", "Mutarakwa"],
  "Bomet East": ["Merigi", "Kembu", "Longisa", "Kipreres", "Chemaner"],
  "Chepalungu": ["Sigor", "Chebunyo", "Siongiroi", "Nyangores", "Kipsonoi"],
  "Konoin": ["Kimulot", "Mogogosiek", "Boito", "Chepchabas", "Embomos"],
  "Sotik": ["Ndanai/Abosi", "Chemagel", "Kapletundo", "Manaret", "Rongena/Manaret"]
  },
  "Kakamega": {
  "Lugari": ["Lugari", "Lumakanda", "Chekalini", "Chevaywa", "Lwandeti"],
  "Likuyani": ["Likuyani", "Sango", "Kongoni", "Nzoia", "Sinoko"],
  "Malava": ["Malava", "Shivagala", "Shivanga", "Kabras", "South Kabras"],
  "Lurambi": ["Lurambi", "Shinyalu", "Idakho", "Mahiakalo", "Maraba"],
  "Navakholo": ["Navakholo", "Kabras", "Shivanga", "Kabras South", "Kabras West"],
  "Mumias West": ["Mumias", "Mumias Central", "Mumias East", "Mumias West", "Mumias South"],
  "Mumias East": ["Mumias", "Mumias Central", "Mumias East", "Mumias West", "Mumias South"],
  "Matungu": ["Matungu", "Shivanga", "Shivagala", "Kabras", "South Kabras"],
  "Butere": ["Butere", "Shivanga", "Shivagala", "Kabras", "South Kabras"],
  "Khwisero": ["Khwisero", "Shivanga", "Shivagala", "Kabras", "South Kabras"],
  "Shinyalu": ["Shinyalu", "Idakho", "Mahiakalo", "Maraba", "Lurambi"],
  "Ikolomani": ["Ikolomani", "Idakho", "Mahiakalo", "Maraba", "Lurambi"]
  },
  "Vihiga": {
  "Sabatia": ["Lyaduywa/Izava", "Wodanga", "Chavakali", "North Maragoli", "Busali"],
  "Hamisi": ["Shiru", "Gisambai", "Shamakhokho", "Banja", "Muhudu", "Tambua", "Jepkoyai"],
  "Luanda": ["Luanda Township", "Wemilabi", "Mwibona", "Emabungo", "Ekwanda"],
  "Emuhaya": ["North East Bunyore", "Central Bunyore", "West Bunyore"],
  "Vihiga": ["Lugaga-Wamuluma", "South Maragoli", "Central Maragoli", "Mungoma", "Lyamoywa"]
  },
  "Bungoma": {
  "Kanduyi": ["Bukembe West", "Bukembe East", "Township", "Khalaba", "Musikoma", "East Sang'alo", "Marakaru/Tuuti", "West Sang'alo"],
  "Webuye East": ["Mihuu", "Ndivisi", "Maraka"],
  "Webuye West": ["Misikhu", "Sitikho", "Matulo"],
  "Kimilili": ["Maeni", "Kamukuywa", "Kimilili", "Milima"],
  "Sirisia": ["Namwela", "Malakisi/South Kulisiru", "Lwandanyi"],
  "Kabuchai": ["Mukuyuni", "West Nalondo", "Bwake/Luuya", "Chwele/Kabuchai"],
  "Tongaren": ["Milima", "Ndalu/Tabani", "Tongaren", "Soysambu/Mitua", "Naitiri/Kabuyefwe"],
  "Bumula": ["Bumula", "Khasoko", "Kabula", "Kimaeti", "South Bukusu", "West Bukusu", "Siboti"]
  },
  "Busia": {
  "Teso North": ["Malaba Central", "Malaba North", "Angurai South", "Angurai North", "Angurai East"],
  "Teso South": ["Ang'urai West", "Chakol South", "Chakol North", "Amukura East", "Amukura West", "Amukura Central"],
  "Nambale": ["Bukhayo North/Waltsi", "Bukhayo East", "Bukhayo Central", "Bukhayo West"],
  "Matayos": ["Busibwabo", "Mayenje", "Matayos South", "Matayos Central", "Burumba"],
  "Butula": ["Marachi West", "Marachi Central", "Marachi East", "Kingandole", "Elugulu"],
  "Funyula": ["Bwiri", "Namboboto/Nambuku", "Ageng'a/Nanguba", "Nangina"],
  "Bunyala": ["Bunyala Central", "Bunyala North", "Bunyala West", "Bunyala South"]
  },
  "Siaya": {
  "Ugenya": ["East Ugenya", "North Ugenya", "West Ugenya", "South Ugenya"],
  "Ugunja": ["Ugunja", "Sigomere", "Sidindi"],
  "Alego Usonga": ["Usonga", "West Alego", "Central Alego", "Siaya Township", "North Alego", "South East Alego"],
  "Gem": ["North Gem", "South Gem", "East Gem", "Yala Township", "Central Gem"],
  "Bondo": ["West Yimbo", "Central Sakwa", "South Sakwa", "Yimbo East", "North Sakwa", "West Sakwa"],
  "Rarieda": ["East Asembo", "West Asembo", "North Uyoma", "South Uyoma", "West Uyoma"]
  },
  "Kisumu": {
  "Kisumu East": ["Kajulu", "Kolwa East", "Manyatta B"],
  "Kisumu West": ["North West Kisumu", "Kisumu North", "Kisumu Central", "South West Kisumu", "Central Kisumu"],
  "Kisumu Central": ["Railways", "Shaurimoyo Kaloleni", "Market Milimani", "Nyalenda A", "Nyalenda B", "Migosi"],
  "Nyando": ["East Kano/Wawidhi", "Awasi/Onjiko", "Ahero", "Kabonyo/Kanyagwal", "Kobura"],
  "Muhoroni": ["Chemelil", "Muhoroni/Koru", "Owasa", "Fort Ternan"],
  "Nyakach": ["South East Nyakach", "North Nyakach", "West Nyakach", "Central Nyakach"],
  "Seme": ["West Seme", "East Seme", "Central Seme", "North Seme"]
  },
  "Homa Bay": {
  "Homa Bay Town": ["Homa Bay Central", "Homa Bay Arujo", "Homa Bay East", "Homa Bay West"],
  "Kabondo Kasipul": ["Kabondo East", "Kabondo West", "Kokwanyo/Kakelo", "Kojwach"],
  "Karachuonyo": ["West Karachuonyo", "North Karachuonyo", "Kanyaluo", "Central Karachuonyo", "Kibiri", "Wang’chieng’", "Kendu Bay Town"],
  "Kasipul": ["West Kasipul", "South Kasipul", "Central Kasipul", "East Kamagak", "West Kamagak"],
  "Ndhiwa": ["Kanyadoto", "Kanyikela", "Kabuoch South/Pala", "Kabuoch North", "Kanyamwa Kologi", "Kanyamwa Kosewe", "Kochia"],
  "Rangwe": ["Kagan", "Kochia", "Homa Bay East", "Homa Bay West"],
  "Suba North": ["Mfangano Island", "Rusinga Island", "Kasgunga", "Gembe", "Lambwe"],
  "Suba South": ["Gwassi South", "Gwassi North", "Kaksingri West", "Ruma-Kaksingri"]
  },
  "Migori": {
  "Awendo": ["North Sakwa", "South Sakwa", "Central Sakwa", "West Sakwa"],
  "Kuria East": ["Nyabasi East", "Nyabasi West", "Gokeharaka/Getambwega", "Ntimaru East", "Ntimaru West"],
  "Kuria West": ["Bukira East", "Bukira Central/Ikerege", "Isibania", "Makerero", "Masaba", "Tagare", "Nyamosense/Komosoko"],
  "Nyatike": ["Kachieng'", "Kanyasa", "North Kadem", "Macalder/Kanyarwanda", "Kaler", "Got Kachola", "Muhuru"],
  "Rongo": ["North Kamagambo", "Central Kamagambo", "East Kamagambo", "South Kamagambo"],
  "Suna East": ["God Jope", "Suna Central", "Kakrao", "Kwa"],
  "Suna West": ["Wiga", "Wasweta II", "Ragana-Oruba", "Wasimbete"],
  "Uriri": ["West Kanyamkago", "North Kanyamkago", "Central Kanyamkago", "South Kanyamkago", "East Kanyamkago"]
  },
  Kisii: {
  "Bonchari": ["Bomariba", "Bogiakumu", "Riana", "Bomariba East"],
  "South Mugirango": ["Tabaka", "Bogetenga", "Boikang’a", "Moticho"],
  "Bobasi": ["Masige East", "Masige West", "Nyacheki", "Bobasi Central", "Bobasi East"],
  "Bomachoge Borabu": ["Magenche", "Bokimonge", "Nyabasi West", "Nyabasi East"],
  "Bomachoge Chache": ["Bosoti", "Majoge", "Boochi", "Boochi Borabu"],
  "Nyaribari Masaba": ["Kiamokama", "Masimba", "Gesusu", "Rigoma"],
  "Nyaribari Chache": ["Kisii Central", "Kiogoro", "Mosocho", "Bobaracho"],
  "Kitutu Chache North": ["Marani", "Kegogi", "Sensi", "Nyatieko"],
  "Kitutu Chache South": ["Bogusero", "Bogeka", "Nyakoe", "Nyankoba"]
  },
  Nyamira: {
  "Kitutu Masaba": ["Magombo", "Gachuba", "Gesima", "Kemera", "Rigoma"],
  "West Mugirango": ["Nyamaiya", "Bogichora", "Township", "Bosamaro"],
  "North Mugirango": ["Ekerenyo", "Magwagwa", "Bomwagamo", "Itibo"],
  "Borabu": ["Esise", "Mekenene", "Kiabonyoru", "Nyansiongo"]
  },
  Nairobi: {
  "Westlands": ["Kitisuru", "Parklands/Highridge", "Karura", "Kangemi", "Mountain View"],
  "Dagoretti North": ["Kilimani", "Kawangware", "Gatina"],
  "Dagoretti South": ["Mutuini", "Riruta", "Uthiru/Ruthimitu", "Waithaka", "Kilimani"],
  "Langata": ["Karen", "Nairobi West", "Mugumo-Ini", "South C", "Nyayo Highrise"],
  "Kibra": ["Laini Saba", "Lindi", "Makina", "Woodley/Kenyatta Golf Course", "Sarang'ombe"],
  "Roysambu": ["Kahawa", "Githurai", "Zimmerman", "Roy Sambu", "Kahawa West"],
  "Kasarani": ["Roysambu", "Githurai", "Kahawa", "Mwiki", "Clay City"],
  "Ruaraka": ["Baba Dogo", "Utalii", "Mathare North", "Lucky Summer", "Korogocho"],
  "Embakasi North": ["Kariobangi North", "Dandora Area I", "Dandora Area II", "Dandora Area III", "Dandora Area IV"],
  "Embakasi South": ["Imara Daima", "Kwa Njenga", "Kwa Reuben", "Pipeline", "Kware"],
  "Embakasi Central": ["Kayole North", "Kayole Central", "Kayole South", "Komarock", "Matopeni/Spring Valley"],
  "Embakasi East": ["Upper Savanna", "Lower Savanna", "Embakasi", "Utawala", "Mihango"],
  "Embakasi West": ["Umoja I", "Umoja II", "Mowlem", "Kariobangi South"],
  "Makadara": ["Maringo/Hamza", "Viwandani", "Harambee", "Makongeni", "Pumwani"],
  "Kamukunji": ["Pumwani", "Eastleigh North", "Eastleigh South", "Airbase", "California"],
  "Starehe": ["Pangani", "Ziwani/Kariokor", "Ngara", "Nairobi Central", "Landimawe"],
  "Mathare": ["Hospital", "Mabatini", "Huruma", "Ngei", "Mlango Kubwa"]
  }
  };

function loadConstituency() {
  const county = document.getElementById("county").value;
  const constituencySelect = document.getElementById("constituency");
  const wardSelect = document.getElementById("ward");

  // Clear previous options
  constituencySelect.innerHTML = '<option value="" hidden>-- Select Constituency --</option>';
  wardSelect.innerHTML = '<option value="" hidden>-- Select Ward --</option>';

  if (county && data[county]) {
  for (let constituency in data[county]) {
    let opt = document.createElement("option");
    opt.value = constituency;
    opt.innerHTML = constituency;
    constituencySelect.appendChild(opt);
  }
  }
  }

  function loadWard() {
  const county = document.getElementById("county").value;
  const constituency = document.getElementById("constituency").value;
  const wardSelect = document.getElementById("ward");

  // Clear previous options
  wardSelect.innerHTML = '<option value="" hidden>-- Select Ward --</option>';

  if (county && constituency && data[county][constituency]) {
  data[county][constituency].forEach(ward => {
    let opt = document.createElement("option");
    opt.value = ward;
    opt.innerHTML = ward;
    wardSelect.appendChild(opt);
  });
  }
  }

  // Function to handle multiple files selection

  function handleFiles(event) {
    const files = event.target.files;  // Get all selected files
    const previewContainer = document.getElementById('filePreviews');
    previewContainer.innerHTML = '';  // Clear previous previews

    let imageCount = 0; // Keep track of how many images we preview

    Array.from(files).forEach(file => {
      const fileSizeInMB = (file.size / (1024 * 1024)).toFixed(2);  // Convert to MB
      const fileType = file.type;

      // Create a container for each file's preview and size
      const fileContainer = document.createElement('div');
      fileContainer.style.marginBottom = '30px';

      // Display the file size
      const fileSizeElement = document.createElement('p');
      fileSizeElement.textContent = `File size: ${fileSizeInMB} MB`;
      fileContainer.appendChild(fileSizeElement);

      // Preview the file based on type
      if (fileType.startsWith('image/')) {
        if (imageCount >= 3) {
          const warning = document.createElement('p');
          warning.style.color = 'red';
          warning.textContent = 'You can only upload 3 images at a time.';
          previewContainer.appendChild(warning);
          return;
        }

        const img = document.createElement('img');
        img.style.width = '70%';
        img.style.display = 'flex';
        img.src = URL.createObjectURL(file);
        img.onload = function () {
          URL.revokeObjectURL(img.src); // Free memory
        };

        fileContainer.appendChild(img);
        imageCount++;



      } else if (fileType === 'application/pdf') {
        const pdfEmbed = document.createElement('embed');
        pdfEmbed.style.width = '100%';
        pdfEmbed.style.height = '100%';
        pdfEmbed.src = URL.createObjectURL(file);
        fileContainer.appendChild(pdfEmbed);

      }

      else {
        const fileName = document.createElement('p');
        fileName.textContent = `File: ${file.name}`;
        fileContainer.appendChild(fileName);
      }
      // Append the file container to the previews section
      previewContainer.appendChild(fileContainer);
    });
  }

   //Show Individual Building Owner
   function _0x36dd() {
    var _0x297681 = ['block', '250JoGcJc', '5948600VuKOnm', 'Last\x20Name\x20Required\x20Before\x20you\x20Close', 'none', '4414LEGmEU', '4686780xABWVD', '50860PStEag', '295FDnylZ', '4476042GJSquo', '#lastName', 'Phone\x20Number\x20Required\x20before\x20you\x20Close', '3749625pGSygv', 'Owner\x20Email\x20Required\x20before\x20you\x20Close', '38676ANLuap', 'style', '#individualCloseBtn', 'Last\x20Name\x20Last\x20Name\x20can\x27t\x20be\x20the\x20same\x20as\x20First\x20Name', 'display', '#phoneNumber', 'val', 'click'];
    _0x36dd = function() {
        return _0x297681;
    };
    return _0x36dd();
}(function(_0x27219b, _0x3bf112) {
    var _0x1b014f = _0x37d4,
        _0x2aea38 = _0x27219b();
    while (!![]) {
        try {
            var _0x2ccf21 = -parseInt(_0x1b014f(0x6e)) / 0x1 * (parseInt(_0x1b014f(0x81)) / 0x2) + parseInt(_0x1b014f(0x74)) / 0x3 + -parseInt(_0x1b014f(0x6d)) / 0x4 * (-parseInt(_0x1b014f(0x7d)) / 0x5) + parseInt(_0x1b014f(0x6f)) / 0x6 + -parseInt(_0x1b014f(0x82)) / 0x7 + parseInt(_0x1b014f(0x7e)) / 0x8 + -parseInt(_0x1b014f(0x72)) / 0x9;
            if (_0x2ccf21 === _0x3bf112) break;
            else _0x2aea38['push'](_0x2aea38['shift']());
        } catch (_0x1a8e11) {
            _0x2aea38['push'](_0x2aea38['shift']());
        }
    }
}(_0x36dd, 0x61e62));

function _0x37d4(_0x33f868, _0x3f4260) {
    var _0x36dd4c = _0x36dd();
    return _0x37d4 = function(_0x37d4f6, _0x1d7341) {
        _0x37d4f6 = _0x37d4f6 - 0x6d;
        var _0x143ff0 = _0x36dd4c[_0x37d4f6];
        return _0x143ff0;
    }, _0x37d4(_0x33f868, _0x3f4260);
}

function showIndividualOwner() {
    var _0x39227a = _0x37d4,
        _0x1ce2d4 = document['getElementById']('individualInfoDiv');
    individualInfoDiv[_0x39227a(0x75)]['display'] = _0x39227a(0x7c), entityInfoDiv[_0x39227a(0x75)][_0x39227a(0x78)] = _0x39227a(0x80), $(_0x39227a(0x76))[_0x39227a(0x7b)](function(_0x551cb9) {
        var _0x458d9a = _0x39227a;
        _0x551cb9['preventDefault']();
        if ($('#firstName')[_0x458d9a(0x7a)]() == '') return alert('First\x20Name\x20Required\x20Before\x20you\x20Close'), ![];
        else {
            if ($(_0x458d9a(0x70))['val']() == '') return alert(_0x458d9a(0x7f)), ![];
            else {
                if ($(_0x458d9a(0x70))[_0x458d9a(0x7a)]() == $('#firstName')['val']()) return alert(_0x458d9a(0x77)), ![];
                else {
                    if ($(_0x458d9a(0x79))[_0x458d9a(0x7a)]() == '') return alert(_0x458d9a(0x71)), ![];
                    else {
                        if ($('#ownerEmail')[_0x458d9a(0x7a)]() == '') return alert(_0x458d9a(0x73)), ![];
                        else individualInfoDiv[_0x458d9a(0x75)]['display'] = _0x458d9a(0x80);
                    }
                }
            }
        }
    });
}

//Show Entity as the Building Owner
(function(_0x248a5e, _0x2727e2) {
    var _0x17728a = _0x5035,
        _0x2894f8 = _0x248a5e();
    while (!![]) {
        try {
            var _0x25f3ba = parseInt(_0x17728a(0xb9)) / 0x1 * (-parseInt(_0x17728a(0xc3)) / 0x2) + parseInt(_0x17728a(0xaf)) / 0x3 + parseInt(_0x17728a(0xc1)) / 0x4 + parseInt(_0x17728a(0xb4)) / 0x5 * (-parseInt(_0x17728a(0xbe)) / 0x6) + -parseInt(_0x17728a(0xb3)) / 0x7 + parseInt(_0x17728a(0xb1)) / 0x8 * (-parseInt(_0x17728a(0xc0)) / 0x9) + -parseInt(_0x17728a(0xc6)) / 0xa * (-parseInt(_0x17728a(0xc2)) / 0xb);
            if (_0x25f3ba === _0x2727e2) break;
            else _0x2894f8['push'](_0x2894f8['shift']());
        } catch (_0x3259f) {
            _0x2894f8['push'](_0x2894f8['shift']());
        }
    }
}(_0x5cef, 0x27563));

function _0x5035(_0x137470, _0x2029d9) {
    var _0x5cefd4 = _0x5cef();
    return _0x5035 = function(_0x5035f1, _0x13c67d) {
        _0x5035f1 = _0x5035f1 - 0xae;
        var _0x528b8f = _0x5cefd4[_0x5035f1];
        return _0x528b8f;
    }, _0x5035(_0x137470, _0x2029d9);
}

function _0x5cef() {
    var _0x1e8c55 = ['val', '#entityRepRole', 'Entity\x20Representative\x20Role\x20Required', '490008NxgpPG', 'style', '8eOyUJB', '#entityRepresentative', '199570ujpDry', '102535ZYWBin', 'display', '#entityEmail', '#entityName', 'preventDefault', '32914mYDHti', 'Entity\x20Representative\x20Required', 'getElementById', 'click', 'entityInfoDiv', '84ZwtMuv', 'Entity\x20Email\x20Required\x20before\x20you\x20Close', '300249eFTGAF', '17236EznKiF', '641575xHySxy', '4bYxigF', 'block', 'Entity\x20Name\x20Required\x20before\x20you\x20Close', '70FgpscT'];
    _0x5cef = function() {
        return _0x1e8c55;
    };
    return _0x5cef();
}

function showEntityOwner() {
    var _0x543ff9 = _0x5035,
        _0x398092 = document[_0x543ff9(0xbb)](_0x543ff9(0xbd));
    entityInfoDiv[_0x543ff9(0xb0)][_0x543ff9(0xb5)] = _0x543ff9(0xc4), individualInfoDiv[_0x543ff9(0xb0)][_0x543ff9(0xb5)] = 'none', $('#entityCloseDivBtn')[_0x543ff9(0xbc)](function(_0x281c02) {
        var _0x1eebcc = _0x543ff9;
        _0x281c02[_0x1eebcc(0xb8)]();
        if ($(_0x1eebcc(0xb7))[_0x1eebcc(0xc7)]() == '') return alert(_0x1eebcc(0xc5)), ![];
        else {
            if ($('#entityPhone')[_0x1eebcc(0xc7)]() == '') return alert('Entity\x20Phone\x20Number\x20Required\x20before\x20you\x20Close'), ![];
            else {
                if ($(_0x1eebcc(0xb6))[_0x1eebcc(0xc7)]() == '') return alert(_0x1eebcc(0xbf)), 0x0;
                else {
                    if ($(_0x1eebcc(0xb2))['val']() == '') return alert(_0x1eebcc(0xba)), ![];
                    else {
                        if ($(_0x1eebcc(0xc8))[_0x1eebcc(0xc7)]() == '') return alert(_0x1eebcc(0xae)), ![];
                        else entityInfoDiv[_0x1eebcc(0xb0)][_0x1eebcc(0xb5)] = 'none';
                    }
                }
            }
        }
    });
}
// </script>

// <!-- Construction Authority Displays DOM -->
{/* <script> */}
//NCA Approval
var _0x1c618d = _0x6d0b;
(function(_0x4961e9, _0x5a9f03) {
    var _0x1ea8bc = _0x6d0b,
        _0x145c4b = _0x4961e9();
    while (!![]) {
        try {
            var _0x2c1770 = parseInt(_0x1ea8bc(0x99)) / 0x1 * (-parseInt(_0x1ea8bc(0x9f)) / 0x2) + -parseInt(_0x1ea8bc(0xac)) / 0x3 + parseInt(_0x1ea8bc(0x98)) / 0x4 * (-parseInt(_0x1ea8bc(0x9e)) / 0x5) + -parseInt(_0x1ea8bc(0x9d)) / 0x6 * (-parseInt(_0x1ea8bc(0xad)) / 0x7) + -parseInt(_0x1ea8bc(0xa8)) / 0x8 + -parseInt(_0x1ea8bc(0x9b)) / 0x9 * (-parseInt(_0x1ea8bc(0xab)) / 0xa) + parseInt(_0x1ea8bc(0xa0)) / 0xb * (parseInt(_0x1ea8bc(0xa5)) / 0xc);
            if (_0x2c1770 === _0x5a9f03) break;
            else _0x145c4b['push'](_0x145c4b['shift']());
        } catch (_0x3e24e4) {
            _0x145c4b['push'](_0x145c4b['shift']());
        }
    }
}(_0x3c16, 0xeedaa));
var ncaApprivalCardSection = document[_0x1c618d(0x96)](_0x1c618d(0xaf));

function attachNcaApproval() {
    var _0x3830a0 = _0x1c618d;
    ncaApprivalCardSection[_0x3830a0(0xa9)][_0x3830a0(0xa2)] = _0x3830a0(0x97), $(_0x3830a0(0x9c))[_0x3830a0(0xa7)](function(_0x4abce5) {
        var _0xc8837f = _0x3830a0;
        _0x4abce5[_0xc8837f(0xae)]();
        if ($(_0xc8837f(0xa3))[_0xc8837f(0x9a)]() == '') return alert(_0xc8837f(0xa6)), ![];
        else {
            if ($(_0xc8837f(0xa4))[_0xc8837f(0x9a)]() == '') return alert('Approval\x20Date\x20Required'), ![];
            else {
                if ($(_0xc8837f(0xaa))[_0xc8837f(0x9a)]() == '') return alert('Approval\x20Copy\x20Required'), ![];
                else ncaApprivalCardSection['style'][_0xc8837f(0xa2)] = _0xc8837f(0xa1);
            }
        }
    });
}

function _0x6d0b(_0x2614bb, _0x1a47ea) {
    var _0x3c16ba = _0x3c16();
    return _0x6d0b = function(_0x6d0bfa, _0x534c83) {
        _0x6d0bfa = _0x6d0bfa - 0x96;
        var _0x498091 = _0x3c16ba[_0x6d0bfa];
        return _0x498091;
    }, _0x6d0b(_0x2614bb, _0x1a47ea);
}

function closeAttachNcaApproval() {
    var _0x1df058 = _0x1c618d;
    ncaApprivalCardSection[_0x1df058(0xa9)][_0x1df058(0xa2)] = _0x1df058(0xa1);
}

function _0x3c16() {
    var _0x262f43 = ['24xYoPmZ', 'Construction\x20Authority\x20Number\x20Required', 'click', '3016344TBruza', 'style', '#ncaApprovalCopy', '70KfaFVK', '5358915PugGBN', '35SBLlew', 'preventDefault', 'ncaApprivalCard', 'getElementById', 'block', '8WeVnmq', '5ibRhVM', 'val', '483489qHPFNl', '#closeNcaApprovalBtn', '474954qmwmyj', '2928630aBmqSG', '159716tbmaMI', '21673267yvkBqG', 'none', 'display', '#approvalNo', '#approvalDate'];
    _0x3c16 = function() {
        return _0x262f43;
    };
    return _0x3c16();
}

//NEMA Approval DOM
function _0x1cda() {
    var _0x2bcbdc = ['346023KAfBrV', '341664bwPXya', 'val', '#nemaApprovalDate', 'block', '86109gwHfSj', '4PltjcO', 'none', 'NEMA\x20Approval\x20Date\x20Required', 'nemaApprovalSpecify', 'preventDefault', 'display', '2864045tyVyLW', '2873610lwNUEb', '192ikxrtg', 'click', '1882069KeSReU', 'style', '495692VztPJI', '#nemaApprovalNumber', '#closeNemaApproval'];
    _0x1cda = function() {
        return _0x2bcbdc;
    };
    return _0x1cda();
}
var _0x2c8272 = _0x2ea2;
(function(_0x5c2350, _0x17d1b4) {
    var _0x45c317 = _0x2ea2,
        _0x1cccab = _0x5c2350();
    while (!![]) {
        try {
            var _0x5d4b57 = -parseInt(_0x45c317(0xe3)) / 0x1 + parseInt(_0x45c317(0xe8)) / 0x2 * (parseInt(_0x45c317(0xe7)) / 0x3) + parseInt(_0x45c317(0xdf)) / 0x4 + -parseInt(_0x45c317(0xee)) / 0x5 + parseInt(_0x45c317(0xef)) / 0x6 + -parseInt(_0x45c317(0xf2)) / 0x7 + -parseInt(_0x45c317(0xf0)) / 0x8 * (-parseInt(_0x45c317(0xe2)) / 0x9);
            if (_0x5d4b57 === _0x17d1b4) break;
            else _0x1cccab['push'](_0x1cccab['shift']());
        } catch (_0x110420) {
            _0x1cccab['push'](_0x1cccab['shift']());
        }
    }
}(_0x1cda, 0x61924));
var nemaApprovalSpecifySection = document['getElementById'](_0x2c8272(0xeb));

function _0x2ea2(_0x2f2ba9, _0x3dc41f) {
    var _0x1cda2c = _0x1cda();
    return _0x2ea2 = function(_0x2ea2be, _0x1e0963) {
        _0x2ea2be = _0x2ea2be - 0xdf;
        var _0x1ac9d9 = _0x1cda2c[_0x2ea2be];
        return _0x1ac9d9;
    }, _0x2ea2(_0x2f2ba9, _0x3dc41f);
}

function nemaApprovalShow() {
    var _0x3e9817 = _0x2c8272;
    nemaApprovalSpecifySection[_0x3e9817(0xf3)][_0x3e9817(0xed)] = _0x3e9817(0xe6), $(_0x3e9817(0xe1))[_0x3e9817(0xf1)](function(_0x41d4df) {
        var _0xec7c9e = _0x3e9817;
        _0x41d4df[_0xec7c9e(0xec)]();
        if ($(_0xec7c9e(0xe0))[_0xec7c9e(0xe4)]() == '') return alert('Nema\x20Approval\x20Number\x20Required'), ![];
        else {
            if ($(_0xec7c9e(0xe5))[_0xec7c9e(0xe4)]() == '') return alert(_0xec7c9e(0xea)), ![];
            else {
                if ($('#nemaApprovalCopy')[_0xec7c9e(0xe4)]() == '') return alert('NEMA\x20Approval\x20Copy\x20Required'), ![];
                else nemaApprovalSpecifySection['style'][_0xec7c9e(0xed)] = _0xec7c9e(0xe9);
            }
        }
    });
}

function nemaApprovalHide() {
    var _0x1051b7 = _0x2c8272;
    nemaApprovalSpecifySection[_0x1051b7(0xf3)][_0x1051b7(0xed)] = 'none';
}

//Local Government Specifications DOM
var _0x514fd3 = _0x194b;
(function(_0x143c7b, _0x3e2e5a) {
    var _0x5074ad = _0x194b,
        _0x4afc78 = _0x143c7b();
    while (!![]) {
        try {
            var _0x4128cf = parseInt(_0x5074ad(0x8f)) / 0x1 * (parseInt(_0x5074ad(0x88)) / 0x2) + -parseInt(_0x5074ad(0x80)) / 0x3 + parseInt(_0x5074ad(0x85)) / 0x4 + -parseInt(_0x5074ad(0x81)) / 0x5 * (-parseInt(_0x5074ad(0x92)) / 0x6) + parseInt(_0x5074ad(0x94)) / 0x7 + parseInt(_0x5074ad(0x87)) / 0x8 + -parseInt(_0x5074ad(0x83)) / 0x9 * (parseInt(_0x5074ad(0x96)) / 0xa);
            if (_0x4128cf === _0x3e2e5a) break;
            else _0x4afc78['push'](_0x4afc78['shift']());
        } catch (_0x50a160) {
            _0x4afc78['push'](_0x4afc78['shift']());
        }
    }
}(_0x5af9, 0x914ea));
var localGovSpecificationsSection = document[_0x514fd3(0x93)](_0x514fd3(0x84));

function _0x194b(_0x118b21, _0x3a4f8e) {
    var _0x5af90a = _0x5af9();
    return _0x194b = function(_0x194b18, _0x111a33) {
        _0x194b18 = _0x194b18 - 0x7f;
        var _0x48a932 = _0x5af90a[_0x194b18];
        return _0x48a932;
    }, _0x194b(_0x118b21, _0x3a4f8e);
}

function showLocalGovernmentApproval() {
    var _0x56391d = _0x514fd3;
    localGovSpecificationsSection[_0x56391d(0x82)][_0x56391d(0x8b)] = _0x56391d(0x86), $(_0x56391d(0x91))[_0x56391d(0x8c)](function() {
        var _0x4fe001 = _0x56391d;
        if ($(_0x4fe001(0x8a))['val']() == '') return alert(_0x4fe001(0x90)), ![];
        else {
            if ($(_0x4fe001(0x95))[_0x4fe001(0x8e)]() == '') return alert(_0x4fe001(0x8d)), ![];
            else {
                if ($(_0x4fe001(0x89))[_0x4fe001(0x8e)]() == '') return alert('Local\x20Government\x20Approval\x20Copy\x20Required'), ![];
                else localGovSpecificationsSection[_0x4fe001(0x82)][_0x4fe001(0x8b)] = _0x4fe001(0x7f);
            }
        }
    });
}

function _0x5af9() {
    var _0xbff494 = ['Local\x20Government\x20Approval\x20Number\x20Required', '#closeLocalGovSpecifications', '6MppNfR', 'getElementById', '8094352MNXAtW', '#localGovApprovalDate', '3530fpRMsq', 'none', '159696cLXDtW', '1876370YKDDhM', 'style', '91683thckcI', 'localGovSpecifications', '4370420mhFssb', 'block', '8563352MCOvih', '157082ekJuOe', '#localGovApprovalCopy', '#localGovApprovalNo', 'display', 'click', 'Local\x20Government\x20Approval\x20Date\x20Required', 'val', '7jtmRls'];
    _0x5af9 = function() {
        return _0xbff494;
    };
    return _0x5af9();
}

function hideLocalGovernmentApproval() {
    var _0x5da196 = _0x514fd3;
    localGovSpecificationsSection[_0x5da196(0x82)]['display'] = _0x5da196(0x7f);
}

//Insurance Policy Scripts
function _0x21cb(_0x4f2690, _0x32aca6) {
    var _0xc109c6 = _0xc109();
    return _0x21cb = function(_0x21cb18, _0x29df81) {
        _0x21cb18 = _0x21cb18 - 0x9f;
        var _0x144932 = _0xc109c6[_0x21cb18];
        return _0x144932;
    }, _0x21cb(_0x4f2690, _0x32aca6);
}
var _0x1e19e0 = _0x21cb;
(function(_0x4c8f09, _0x38f0d3) {
    var _0x589c90 = _0x21cb,
        _0x113959 = _0x4c8f09();
    while (!![]) {
        try {
            var _0x5f6e02 = parseInt(_0x589c90(0xa0)) / 0x1 + parseInt(_0x589c90(0xac)) / 0x2 + parseInt(_0x589c90(0xa9)) / 0x3 * (parseInt(_0x589c90(0x9f)) / 0x4) + parseInt(_0x589c90(0xa3)) / 0x5 + parseInt(_0x589c90(0xb2)) / 0x6 * (-parseInt(_0x589c90(0xaa)) / 0x7) + parseInt(_0x589c90(0xae)) / 0x8 + parseInt(_0x589c90(0xb0)) / 0x9 * (-parseInt(_0x589c90(0xa6)) / 0xa);
            if (_0x5f6e02 === _0x38f0d3) break;
            else _0x113959['push'](_0x113959['shift']());
        } catch (_0x45d490) {
            _0x113959['push'](_0x113959['shift']());
        }
    }
}(_0xc109, 0xbad63));

function _0xc109() {
    var _0x35a3d4 = ['#policy_until_date', '#closeInsuranceInfoBtn', '2804UQEFsX', '1462464OTAVNC', 'Please\x20Specify\x20Policy\x20Expiry\x20Date', 'preventDefault', '7035540xsatDb', 'val', 'Insurance\x20Policy\x20Initial\x20Date\x20Required', '5925290mwjMyt', 'style', 'getElementById', '4839BzIiwv', '7sETDLJ', 'display', '2104552OWzbUa', 'none', '9083640Tkimbi', 'Insurance\x20Policy\x20Provider\x20Required', '63TxLyEZ', '#insurance_provider', '7650180WPUXDo'];
    _0xc109 = function() {
        return _0x35a3d4;
    };
    return _0xc109();
}
var specifyInsuranceCoverInfoCardInfo = document[_0x1e19e0(0xa8)]('specifyInsuranceCoverInfoCard');

function insuranceCoverYes() {
    var _0x34ea4d = _0x1e19e0;
    specifyInsuranceCoverInfoCardInfo[_0x34ea4d(0xa7)][_0x34ea4d(0xab)] = 'block', $(_0x34ea4d(0xb4))['click'](function(_0x3eb00c) {
        var _0x224936 = _0x34ea4d;
        _0x3eb00c[_0x224936(0xa2)]();
        if ($('#insurance_policy')[_0x224936(0xa4)]() == '') return alert('Insurance\x20Policy\x20Provider\x20Required'), ![];
        else {
            if ($(_0x224936(0xb1))['val']() == '') return alert(_0x224936(0xaf)), ![];
            else {
                if ($('#policy_from_date')[_0x224936(0xa4)]() == '') return alert(_0x224936(0xa5)), ![];
                else {
                    if ($(_0x224936(0xb3))[_0x224936(0xa4)]() == '') return alert(_0x224936(0xa1)), ![];
                    else specifyInsuranceCoverInfoCardInfo[_0x224936(0xa7)][_0x224936(0xab)] = _0x224936(0xad);
                }
            }
        }
    });
}

function insuranceCoverNo() {
    var _0x2e3351 = _0x1e19e0;
    specifyInsuranceCoverInfoCardInfo[_0x2e3351(0xa7)]['display'] = _0x2e3351(0xad);
}

//Deposits DOM
var depositCardSection = document.getElementById('depositCard');

function showDepositBox() {
    depositCardSection.style.display = 'block';
}

function hideDepositBox() {
    depositCardSection.style.display = 'none';
}

//Step by Step Building Registration and Validations DOM -->
$(document).ready(function() {

    $("#stepOneNextBtn").click(function(e) {
        e.preventDefault();
        $("#sectionTwo").show();
        $("#sectionOne").hide();

        $("#stepOneIndicatorNo").html('<i class="fa fa-check"><i>');
        $("#stepOneIndicatorNo").css('background-color', '#FFC107');
        $("#stepOneIndicatorNo").css('color', '#00192D');
        $("#stepOneIndicatorText").html('Done');
    });

    $("#stepTwoBackBtn").click(function(e) {
        e.preventDefault();
        $("#sectionTwo").hide();
        $("#sectionOne").show();

        $("#stepOneIndicatorNo").html('1');
        $("#stepOneIndicatorNo").css('background-color', '#00192D');
        $("#stepOneIndicatorNo").css('color', '#FFC107');
        $("#stepOneIndicatorText").html('Overview');
    });

    $("#stepTwoNextBtn").click(function(e) {
        e.preventDefault();
        $("#sectionTwo").hide();
        $("#sectionThree").show();

        $("#stepTwoIndicatorNo").html('<i class="fa fa-check"><i>');
        $("#stepTwoIndicatorNo").css('background-color', '#FFC107');
        $("#stepTwoIndicatorNo").css('color', '#00192D');
        $("#stepTwoIndicatorText").html('Done');
    });

    $("#stepThreeBackBtn").click(function(e) {
        e.preventDefault();
        $("#sectionTwo").show();
        $("#sectionThree").hide();

        $("#stepTwoIndicatorNo").html('2');
        $("#stepTwoIndicatorNo").css('background-color', '#00192D');
        $("#stepTwoIndicatorNo").css('color', '#FFC107');
        $("#stepTwoIndicatorText").html('Identification');
    });

    $("#stepThreeNextBtn").click(function(e) {
        e.preventDefault();
        $("#sectionThree").hide();
        $("#sectionFour").show();

        $("#stepThreeIndicatorNo").html('<i class="fa fa-check"><i>');
        $("#stepThreeIndicatorNo").css('background-color', '#FFC107');
        $("#stepThreeIndicatorNo").css('color', '#00192D');
        $("#stepThreeIndicatorText").html('Done');
    });

    $("#stepFourBackBtn").click(function(e) {
        e.preventDefault();
        $("#sectionThree").show();
        $("#sectionFour").hide();

        $("#stepThreeIndicatorNo").html('3');
        $("#stepThreeIndicatorNo").css('background-color', '#00192D');
        $("#stepThreeIndicatorNo").css('color', '#FFC107');
        $("#stepThreeIndicatorText").html('Ownership');
    });

    $("#stepFourNextBtn").click(function(e) {
        e.preventDefault();
        $("#sectionFour").hide();
        $("#sectionFive").show();

        $("#stepFourIndicatorNo").html('<i class="fa fa-check"><i>');
        $("#stepFourIndicatorNo").css('background-color', '#FFC107');
        $("#stepFourIndicatorNo").css('color', '#00192D');
        $("#stepFourIndicatorText").html('Done');
    });

    $("#stepFiveBackBtn").click(function(e) {
        e.preventDefault();
        $("#sectionFour").show();
        $("#sectionFive").hide();

        $("#stepFourIndicatorNo").html('4');
        $("#stepFourIndicatorNo").css('background-color', '#00192D');
        $("#stepFourIndicatorNo").css('color', '#FFC107');
        $("#stepFourIndicatorText").html('Utilities');
    });

    $("#stepFiveNextBtn").click(function(e) {
        e.preventDefault();
        $("#sectionFive").hide();
        $("#sectionSix").show();

        $("#stepFiveIndicatorNo").html('<i class="fa fa-check"><i>');
        $("#stepFiveIndicatorNo").css('background-color', '#FFC107');
        $("#stepFiveIndicatorNo").css('color', '#00192D');
        $("#stepFiveIndicatorText").html('Done');
    });

    $("#stepSixBackBtn").click(function(e) {
        e.preventDefault();
        $("#sectionFive").show();
        $("#sectionSix").hide();

        $("#stepFiveIndicatorNo").html('5');
        $("#stepFiveIndicatorNo").css('background-color', '#00192D');
        $("#stepFiveIndicatorNo").css('color', '#FFC107');
        $("#stepFiveIndicatorText").html('Regulations');
    });

    $("#stepSixNextBtn").click(function(e) {
        e.preventDefault();
        $("#sectionSix").hide();
        $("#sectionSeven").show();

        $("#stepSixIndicatorNo").html('<i class="fa fa-check"><i>');
        $("#stepSixIndicatorNo").css('background-color', '#FFC107');
        $("#stepSixIndicatorNo").css('color', '#00192D');
        $("#stepSixIndicatorText").html('Done');
    });

    $("#stepSevenBackBtn").click(function(e) {
        e.preventDefault();
        $("#sectionSix").show();
        $("#sectionSeven").hide();

        $("#stepSixIndicatorNo").html('6');
        $("#stepSixIndicatorNo").css('background-color', '#00192D');
        $("#stepSixIndicatorNo").css('color', '#FFC107');
        $("#stepSixIndicatorText").html('Insurance');
    });

    $("#stepSevenNextBtn").click(function(e) {
        e.preventDefault();
        $("#sectionSeven").hide();
        $("#sectionEight").show();

        $("#stepSevenIndicatorNo").html('<i class="fa fa-check"><i>');
        $("#stepSevenIndicatorNo").css('background-color', '#FFC107');
        $("#stepSevenIndicatorNo").css('color', '#00192D');
        $("#stepSevenIndicatorText").html('Done');
    });

    $("#stepEightBackBtn").click(function(e) {
        e.preventDefault();
        $("#sectionSeven").show();
        $("#sectionEight").hide();

        $("#stepSevenIndicatorNo").html('7');
        $("#stepSevenIndicatorNo").css('background-color', '#00192D');
        $("#stepSevenIndicatorNo").css('color', '#FFC107');
        $("#stepSevenIndicatorText").html('Photos');
    });

});



// data tables
  $(document).ready(function () {
      $('#myTableOne').DataTable();
  });
  $(document).ready(function () {
      $('#myTableThree').DataTable();
  });
  $(document).ready(function () {
      $('#myTableFour').DataTable();
  });


  $(document).ready(function() {
$('#myTable').DataTable({
 "paging": true,
 "searching": true,
 "info": true,
 "lengthMenu": [5, 10, 25, 50],
 "language": {
     "search": "Filter records:",
     "lengthMenu": "Show _MENU_ entries"
 }
});
});

// sidebar wrapper

const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
  const Default = {
    scrollbarTheme: 'os-theme-light',
    scrollbarAutoHide: 'leave',
    scrollbarClickScroll: true,
  };
  document.addEventListener('DOMContentLoaded', function () {
    const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
    if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
      OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
        scrollbars: {
          theme: Default.scrollbarTheme,
          autoHide: Default.scrollbarAutoHide,
          clickScroll: Default.scrollbarClickScroll,
        },
      });
    }
  });

// sidebar
fetch('../bars/sidebar.html')  // Fetch the file
  .then(response => response.text()) // Convert it to text
  .then(data => {
      document.getElementById('sidebar').innerHTML = data; // Insert it
  })
  .catch(error => console.error('Error loading the file:', error)); // Handle errors

  // const cty = document.getElementById('rentalTrends').getContext('2d');
// end

const input = document.getElementById('first_name').value;
if (!validateFirstName(input)) {
  alert("First name must contain letters only.");
}



// solar

function _0x46b7() {
  var _0x4d7a85 = ['#installationCompany', '1268184PtZbpo', '6WXLSBR', '3617070ViYaqI', 'style', '#solarPrimaryUse', 'click', 'Solar\x20Brand\x20Name\x20Required\x20before\x20you\x20Close', '#solarBrand', 'getElementById', '#closeSolarProviderBtn', 'none', 'specifySolarPrivider', '63ItAneg', '5tMnpQg', 'display', '669104XTYtnu', 'val', 'Please\x20Specify\x20the\x20Number\x20of\x20Panels\x20Before\x20you\x20Close', '3939008hxyIIU', '4167016KTLqpW', '783772GrtLiz', '#noOfPanels', 'Specify\x20the\x20Primary\x20Use\x20of\x20the\x20Solar\x20Panels\x20before\x20you\x20Close', '1369736CtNyTD', 'preventDefault'];
  _0x46b7 = function() {
      return _0x4d7a85;
  };
  return _0x46b7();
}
var _0x304f93 = _0x4710;
(function(_0x3dc99b, _0xb5c8d9) {
  var _0xbc71ec = _0x4710,
      _0x1db688 = _0x3dc99b();
  while (!![]) {
      try {
          var _0x42097f = -parseInt(_0xbc71ec(0xa8)) / 0x1 + parseInt(_0xbc71ec(0xad)) / 0x2 + -parseInt(_0xbc71ec(0xb3)) / 0x3 + -parseInt(_0xbc71ec(0xb0)) / 0x4 * (parseInt(_0xbc71ec(0xa6)) / 0x5) + -parseInt(_0xbc71ec(0xb4)) / 0x6 * (parseInt(_0xbc71ec(0xac)) / 0x7) + -parseInt(_0xbc71ec(0xab)) / 0x8 + -parseInt(_0xbc71ec(0xa5)) / 0x9 * (-parseInt(_0xbc71ec(0xb5)) / 0xa);
          if (_0x42097f === _0xb5c8d9) break;
          else _0x1db688['push'](_0x1db688['shift']());
      } catch (_0x2fdad5) {
          _0x1db688['push'](_0x1db688['shift']());
      }
  }
}(_0x46b7, 0x621f1));
var specifySolarPrividerSection = document[_0x304f93(0xbb)](_0x304f93(0xa4));

function _0x4710(_0x58997d, _0x4fa89b) {
  var _0x46b7f9 = _0x46b7();
  return _0x4710 = function(_0x4710ae, _0xbf840a) {
      _0x4710ae = _0x4710ae - 0xa4;
      var _0x11285b = _0x46b7f9[_0x4710ae];
      return _0x11285b;
  }, _0x4710(_0x58997d, _0x4fa89b);
}

function specifySolarProvider() {
  var _0x586ee8 = _0x304f93;
  specifySolarPrivider[_0x586ee8(0xb6)]['display'] = 'block', $(_0x586ee8(0xbc))[_0x586ee8(0xb8)](function(_0x1cbd86) {
      var _0x8954b9 = _0x586ee8;
      _0x1cbd86[_0x8954b9(0xb1)]();
      if ($(_0x8954b9(0xba))['val']() == '') return alert(_0x8954b9(0xb9)), ![];
      else {
          if ($(_0x8954b9(0xb2))['val']() == '') return alert('Please\x20Specify\x20Solar\x20Installation\x20Company\x20before\x20you\x20Close'), ![];
          else {
              if ($(_0x8954b9(0xae))['val']() == '') alert(_0x8954b9(0xaa));
              else {
                  if ($(_0x8954b9(0xb7))[_0x8954b9(0xa9)]() == '') return alert(_0x8954b9(0xaf)), ![];
                  else specifySolarPrivider[_0x8954b9(0xb6)][_0x8954b9(0xa7)] = 'none';
              }
          }
      }
  });
}

function hideSolarProvider() {
  var _0x19199a = _0x304f93;
  specifySolarPrivider['style'][_0x19199a(0xa7)] = _0x19199a(0xbd);
}


// new Chart(cty, {
// type: 'line',
// data: {
//   labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
//   datasets: [
//     {
//       label: 'CROWN Z TOWERS',
//       data: [35,'000', 30,'000', 32,'000', 30,'000', 33,'000', 35,'000', 34,'000', 33,'000', 34,'000', 35,'000', 34,'000', 35,'000'],
//       borderColor: 'cyan',
//       backgroundColor: 'transparent',
//       tension: 0.4
//     },
//     {
//       label: 'Manucho Apartments',
//       data: [32,'000', 33,'000', 38,'000', 34,'000', 33,'000', 34,'000', 38,'000', 36,'000', 37,'000', 32,'000', 31,'000', 34,'000'],
//       borderColor: 'green',
//       backgroundColor: 'transparent',
//       tension: 0.4
//     },
//     {
//       label: 'The Mansion Apartments',
//       data:[31,'000', 32,'000', 39,'000', 35,'000', 32,'000', 33,'000', 39,'000', 37,'000', 39,'000', 31,'000', 32,'000', 34,'000'],
//       borderColor: 'black',
//       backgroundColor: 'transparent',
//       tension: 0.4
//     },
//     {
//       label: 'Bsty Apartments',
//       data: [34,'000', 39,'000', 34,'000', 32,'000', 34,'000', 36,'000', 38,'000', 37,'000', 34,'000', 33,'000', 32,'000', 31,'000'],
//       borderColor: 'red',
//       backgroundColor: 'transparent',
//       tension: 0.4
//     }
//   ]
// },
// options: {
//   responsive: true,
//   plugins: {
//     legend: {
//       position: 'top'
//     },

//   },
//   scales: {
//     y: {
//       beginAtZero: false
//     }
//   }
// }
// });

