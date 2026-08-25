@extends('layouts.app')

@section('title', 'شهادة العمل التطوعي المعتمدة — ' . $user->name)

@section('content')
<section class="section" style="padding-top:30px;">
    <div class="container cert-page">
        <div class="cert-actions" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <a href="{{ route('volunteer.dashboard') }}" class="btn btn-ghost btn-sm">&rarr; العودة للوحة التحكم</a>
            <button onclick="window.print()" class="btn btn-green btn-sm">طباعة / حفظ الشهادة PDF</button>
        </div>

        <div class="cert-card">
            <div class="cert-inner">
                <div class="cert-logo">منصة جسور للعمل التطوعي</div>
                <div class="cert-subtitle">National Volunteer Verification Platform</div>

                <div class="cert-title">شهادة خبرة وتطوع معتمدة</div>
                <div class="cert-lead">تشهد إدارة منصة جسور بأن المتطوع / المتطوعة:</div>
                <div class="cert-recipient">{{ $user->name }}</div>

                <p class="cert-text">
                    قد أتم بنجاح وبكفاءة عالية إنجاز الساعات التطوعية المعتمدة والمبينة أدناه من خلال مشاركته الفاعلة في المبادرات والفرص المجتمعية المنظمة عبر المنصة بالتعاون مع المؤسسات والجمعيات الشريكة.
                </p>

                <div class="cert-metrics">
                    <div class="cert-metric">
                        <div class="cm-num">{{ number_format($totalHours, 1) }}</div>
                        <div class="cm-lbl">ساعة تطوع معتمدة</div>
                    </div>
                    <div class="cert-metric">
                        <div class="cm-num">{{ $opportunitiesCount }}</div>
                        <div class="cm-lbl">مبادرة تطوعية</div>
                    </div>
                    <div class="cert-metric">
                        <div class="cm-num">{{ $organizationsCount }}</div>
                        <div class="cm-lbl">مؤسسة شريكة</div>
                    </div>
                </div>

                @if ($participations->isNotEmpty())
                    <div style="margin:24px 0;text-align:right;">
                        <h4 style="color:var(--navy);font-size:14px;margin-bottom:10px;font-weight:700;">سجل المبادرات المعتمدة:</h4>
                        <table class="data" style="font-size:13px;background:#fbfdfc;">
                            <thead>
                                <tr>
                                    <th>المبادرة / الفرصة</th>
                                    <th>المؤسسة المنظمة</th>
                                    <th>التاريخ</th>
                                    <th>الساعات المعتمدة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($participations as $p)
                                    <tr>
                                        <td><strong>{{ $p->opportunity?->title }}</strong></td>
                                        <td>{{ $p->opportunity?->organization?->name }}</td>
                                        <td>{{ $p->work_date?->format('d/m/Y') ?? '—' }}</td>
                                        <td>{{ $p->hours }} ساعة</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="cert-footer">
                    <div class="cert-meta">
                        <div>رقم التوثيق المعتمد: <strong>{{ $certificateNumber }}</strong></div>
                        <div>تاريخ الإصدار: <strong>{{ date('d / m / Y') }}</strong></div>
                        <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">وثيقة إلكترونية رسمية صادرة ومعتمدة من منصة جسور</div>
                    </div>
                    <div class="cert-seal">
                        <span>معتمد<br>جسور</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
