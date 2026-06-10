INASA Membership App — PRD
Project: INASA (Indonesia Australia Scrabble Academy) Membership System
Base Codebase: reinaldyauliakurniawan-arch/membership-app (forked from Gymie)
Stack: Laravel + Filament Admin Panel
Status: Fase 2 in progress — tinggal update MemberForm untuk fields baru, lalu Fase 2 hampir selesai. -> pastikan lagi claim gue jangan asal percaya

1. Background
INASA adalah scrabble club yang baru diinisiasi, fokus pada market Indonesia, dengan sistem keanggotaan dan coaching yang mirip dengan gym. Club rutin mengadakan turnamen minimal sebulan sekali, dan semua turnamen di-rating oleh ISF (Indonesia Scrabble Federation). Member dibagi ke dalam divisi berdasarkan rating ISF masing-masing.

2. Scope of Work
Development dibagi dua fase:

Fase 1 — Cleanup: Hapus dan edit fitur warisan Gymie yang tidak relevan, tanpa menambah fitur baru (DONE)
Fase 2 — Additions: Tambah fitur baru spesifik untuk scrabble club


3. Fase 2 — Fitur Baru
3.1 Model Division (baru)
Tabel: divisions
FieldTipeKeteranganidbigint PKnamestringNama divisi, e.g. "A", "B", "C"min_ratingintegerBatas bawah rating (inclusive)max_ratinginteger nullableBatas atas rating (inclusive). Null = no ceilingdescriptionstring nullableorderintegerUrutan display, Divisi A = 1timestamps
Logic: Saat current_rating member berubah, sistem auto-assign division_id berdasarkan range. Admin bisa override manual.
Filament Resource: CRUD penuh, bisa diakses dari Settings atau menu tersendiri.
3.2 Tambah Fields ke members table
FieldTipeKeteranganisf_idstring nullableID resmi anggota di ISFcurrent_ratinginteger nullableRating ISF saat inidivision_idFK → divisions nullableAuto-assigned dari rating, bisa override manualnationalitystring nullablee.g. WNI, WNA, Dualskill_levelenum nullablebeginner, intermediate, competitiveis_coachbooleanDefault false
Tambahkan juga ke: MemberForm, MemberInfolist, MemberSchema, MemberFactory.
Observer: Buat MemberObserver — saat current_rating di-set atau diupdate, auto-assign division_id yang sesuai berdasarkan tabel divisions.
3.3 Tabel coach_details (baru)
Relasi one-to-one dengan members, hanya untuk member dengan is_coach = true.
FieldTipeKeteranganidbigint PKmember_idFK → membersspecialtystring nullablee.g. "Endgame", "Rack Management", "Opening"biotext nullablehourly_ratedecimal(10,2) nullableTarif per sesitimestamps
Model: CoachDetail. Relasi: Member hasOne CoachDetail, CoachDetail belongsTo Member.
Di Filament MemberForm: saat is_coach di-toggle true, tampilkan section CoachDetail secara kondisional.
3.4 Tabel coaching_sessions (baru)
FieldTipeKeteranganidbigint PKcoach_idFK → membersMember dengan is_coach = truemember_idFK → membersCoacheesession_datedateduration_minutesintegernotestext nullableCatatan sesi dari coachinvoice_idFK → invoices nullableUntuk billingstatusenumscheduled, completed, cancelledtimestamps
Model: CoachingSession dengan relasi ke Member (sebagai coach & coachee) dan Invoice.
Filament Resource: CRUD penuh. Filter by coach, by member, by status, by date range.
3.5 Model Tournament (baru)
Tabel: tournaments
FieldTipeKeteranganidbigint PKnamestringNama turnamendatedatelocationstring nullableformatenumswiss, round_robin, king_of_the_hilldivision_idFK → divisions nullableNull = semua divisiisf_ratedbooleanDefault truestatusenumupcoming, ongoing, completednotestext nullabletimestamps
Filament Resource: CRUD penuh. Status diupdate manual oleh admin.
3.6 Tabel tournament_participants (pivot baru)
FieldTipeKeteranganidbigint PKtournament_idFK → tournamentsmember_idFK → membersdivision_idFK → divisionsDivisi member saat turnamen berlangsungrating_beforeinteger nullableAuto-filled dari member.current_rating saat peserta ditambahkanrating_afterinteger nullableDiisi setelah turnamen selesaifinal_rankinteger nullablePosisi akhirtotal_winsinteger nullabletotal_spreadinteger nullableSelisih skor akumulatif, khas scrabblepointsdecimal nullablePoin akhir turnamentimestamps
Model: TournamentParticipant. Relasi: Tournament hasMany TournamentParticipant, Member hasMany TournamentParticipant.
Logic: Saat tournament di-mark completed dan rating_after diisi, update member.current_rating secara otomatis — ini akan men-trigger MemberObserver untuk re-assign divisi.
3.7 Update Plans
Tidak ada perubahan struktur tabel. Update label dan seeder saja:

Subscription plans: Bulanan (30 hari), 3 Bulan (90 hari), 6 Bulan (180 hari), Tahunan (365 hari)
Coaching plans: Per Sesi, Paket 4 Sesi, Paket 8 Sesi


4. Hal yang Tidak Diubah

Struktur Subscription, Invoice, InvoiceTransaction, Expense, Enquiry, FollowUp, User — tetap as-is
Role & permission system (Filament Shield) — tidak disentuh
API routes — tidak disentuh kecuali field yang dihapus di MemberSchema dan EnquirySchema
