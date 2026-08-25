@extends('layouts.app')

@section('title', 'الرئيسية — منصة جسور للتطوع')

@section('content')

<section class="hero">
    <div class="container hero-grid">
        <div>
            <h1>استثمر خبراتك <span>لخدمة المجتمع</span><br>ووثّق مسيرتك التطوعية</h1>
            <p>منصة جسور تصل المتطوعين بالمؤسسات والجمعيات المعتمدة، لتنظيم المبادرات التطوعية، توثيق ساعات العمل، وإصدار شهادات خبرة رسمية معتمدة.</p>
            <div class="hero-cta">
                <a href="{{ route('browse') }}" class="btn btn-green btn-lg">استكشف الفرص المتاحة</a>
                <a href="{{ route('register') }}" class="btn btn-outline btn-lg">انضم إلى المنصة</a>
            </div>
        </div>
        <div class="hero-illustration">
            <div class="hero-quote">منظومة موحدة لإدارة وتوثيق العمل التطوعي</div>
            <p class="hero-mini">سجّل ساعاتك التطوعية، تابع معدل إنجازك نحو أهدافك، واحصل على شهادات موثقة تدعم مسارك المهني والأكاديمي.</p>
        </div>
    </div>
</section>

<section class="stats-strip container">
    <div class="stat-card">
        <div class="num">{{ number_format(max($stats['volunteers'], 1200)) }}+</div>
        <div class="lbl">متطوع مسجل</div>
    </div>
    <div class="stat-card">
        <div class="num">{{ number_format($stats['opportunities']) }}</div>
        <div class="lbl">فرصة تطوعية معتمدة</div>
    </div>
    <div class="stat-card">
        <div class="num">{{ number_format(max($stats['organizations'], 120)) }}+</div>
        <div class="lbl">مؤسسة شريكة</div>
    </div>
    <div class="stat-card">
        <div class="num">{{ number_format($stats['hours']) }}</div>
        <div class="lbl">ساعة تطوعية موثقة</div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <h2>أحدث الفرص التطوعية المعتمدة</h2>
            <p>فرص تطوعية نشطة ومتاحة للتقديم لدى مؤسسات مجتمعية وأهلية معتمدة.</p>
        </div>

        @if ($featuredOpportunities->isNotEmpty())
            <div class="grid-3">
                @foreach ($featuredOpportunities as $opp)
                    <div class="opp-card">
                        <div class="opp-cover">{{ $opp->category?->name ?? 'تطوع مجتمعي' }}</div>
                        <div class="opp-body">
                            <div class="opp-cat">{{ $opp->category?->name ?? 'تطوع' }}</div>
                            <a href="{{ route('opportunities.show', $opp) }}" class="opp-title">{{ $opp->title }}</a>
                            <p class="opp-desc">{{ Str::limit($opp->description, 110) }}</p>
                            <div class="opp-meta">
                                <span>الموقع: {{ $opp->location }}</span>
                                <span>الالتزام: {{ $opp->required_hours }} ساعة</span>
                            </div>
                            <div class="opp-org">{{ $opp->organization?->name }}</div>
                        </div>
                        <div class="opp-foot">
                            <span style="font-size:12.5px;color:var(--text-muted);">المقاعد: {{ $opp->acceptedCount() }} / {{ $opp->max_volunteers }}</span>
                            <a href="{{ route('opportunities.show', $opp) }}" class="btn btn-green btn-sm">عرض التفاصيل</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="panel empty">
                <div class="empty-icon">—</div>
                <p>لا توجد فرص متاحة حالياً. يرجى المتابعة لاحقاً.</p>
            </div>
        @endif
    </div>
</section>

<section class="section" style="background:var(--white);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <div class="container">
        <div class="section-head">
            <h2>ركائز منصة جسور</h2>
            <p>حلول متكاملة تضمن الشفافية والاحترافية في إدارة العمل التطوعي والمجتمعي.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">01</div>
                <h3>توثيق معتمد للساعات</h3>
                <p>نظام إلكتروني لاعتماد وتسجيل ساعات التطوع بإشراف المؤسسات الشريكة وحفظ سجل رقمي للمتطوع.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">02</div>
                <h3>شهادات رسمية</h3>
                <p>إصدار شهادات خبرة تطوعية رسمية معتمدة برقم توثيق إلكتروني يثبت ساعات العمل المنجزة.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">03</div>
                <h3>مؤسسات مرخصة وموثقة</h3>
                <p>تدقيق واعتماد حسابات الجمعيات والمؤسسات لضمان بيئة عمل تطوعية آمنة ومنتجة.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">04</div>
                <h3>متابعة الأهداف ومؤشرات الإنجاز</h3>
                <p>أدوات تتبع شهرية تتيح للمتطوع قياس التزامه وتحقيق معدلات الساعات المستهدفة.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">05</div>
                <h3>توجيه واختيار دقيق</h3>
                <p>تصفية متقدمة حسب التخصص والمدينة ومستوى الالتزام لربط المتطوع بالفرصة الأنسب.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">06</div>
                <h3>لوحات إدارة متقدمة</h3>
                <p>واجهات متكاملة للمؤسسات لإدارة استقبال وفرز الطلبات وإصدار تقارير النشاط المؤسسي.</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:760px;text-align:center;">
        <h2 style="font-size:26px;color:var(--navy);margin-bottom:12px;">انضم إلى منظومة العمل التطوعي</h2>
        <p style="color:var(--text-muted);margin-bottom:26px;font-size:15px;">سواء كنت متطوعاً يسعى لصناعة الأثر، أو مؤسسة تبحث عن كفاءات لدعم مبادراتها — منصة جسور هي وجهتك الموثوقة.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('register') }}" class="btn btn-green btn-lg">إنشاء حساب جديد</a>
            <a href="{{ route('browse') }}" class="btn btn-outline btn-lg">استكشف دليل الفرص</a>
        </div>
    </div>
</section>

@endsection
