{*
 * 2014 - 2021 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License X11
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/mit-license.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2021 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.5.1
 *
 *}

<div>
	<div>
		{if !empty($title)}
			<h3>{$title|pgtrans}</h3>
		{/if}

		{if !empty($message)}
			<p>{$message|pgtrans}</p>
		{/if}

		{if !empty($details)}
			<p>{$details|pgtrans}</p>
		{/if}

		{if !empty($errors)}
			<ul>
				{foreach from=$errors item='error'}
					<li>{$error|pgtrans}.</li>
				{/foreach}
			</ul>
		{/if}

		{if !empty($url)}
			<div>
				<a href="{$url.link|escape:'html':'UTF-8'}" id="redirect_link">{$url.text|pgtrans}</a>
				{if isset($url.reload) && ($url.reload == true)}
					<script type="text/javascript">
						setTimeout(function() {
							let url = document.getElementById('redirect_link').attributes['href'].value;
							window.location.replace(url);
						}, 5000);
					</script>
				{/if}
			</div>
		{/if}
	</div>

	{if ($env === "DEV") && !empty($exceptions)}
		{foreach from=$exceptions item='exception'}
			<hr />
			<div>
				<h4>Exception of type {get_class($exception)|escape:'htmlall':'UTF-8'}</h4>
				<p>In <strong>{$exception->getFile()|escape:'htmlall':'UTF-8'}</strong> at line <strong>{$exception->getLine()|escape:'htmlall':'UTF-8'}</strong></p>
				<p style="font-size: 2em;">{$exception->getMessage()|escape:'htmlall':'UTF-8'}</p>
				<ol class="text-monospace">
					{foreach from=$exception->getTrace() item='trace'}
						<li>
							<strong>
								{if !empty($trace['function'])}
									{$trace['function']|escape:'htmlall':'UTF-8'}
								{else}
									???
								{/if}
							</strong>
							{if !empty($trace['file'])}
								in
								{$trace['file']|escape:'htmlall':'UTF-8'}
								at line
								{if !empty($trace['line'])}
									{$trace['line']|escape:'htmlall':'UTF-8'}
								{/if}
							{/if}
						</li>
					{/foreach}
				</ol>
			</div>
		{/foreach}
	{/if}
</div>
