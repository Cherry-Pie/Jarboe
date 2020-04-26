<!-- User info -->
<div class="login-info">
    <span> <!-- User image size is adjusted inside CSS, it should stay as it -->

        <a href="javascript:void(0);">
            <img src="{{ admin_user()->avatar_url ?: '/vendor/jarboe/img/avatars/default.png' }}" alt="{{ admin_user()->name }}" class="online" style="border: none;"/>
            <span>
                {{ admin_user()->name }}
            </span>
        </a>

    </span>
</div>
<!-- end user info -->
