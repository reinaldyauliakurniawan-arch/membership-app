@if(app()->environment(['local', 'development']))
<div style="background:#f0f9ff;border:1px solid #bae6fd;padding:8px 16px;text-align:center;font-size:13px;color:#0369a1;">
    Dev credentials — Email: <strong>test@example.com</strong> &nbsp;|&nbsp; Password: <strong>test</strong>
</div>
@endif
