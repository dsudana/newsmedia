# AI Keyword Generation Feature

## Overview

The AI Keyword Generation feature uses **Anthropic Claude API** to automatically generate high-quality articles based on keywords you create. This allows you to:

- Define target keywords with SEO intent
- Set article tone and style preferences
- Automatically generate full articles with Claude AI
- Track generation status (pending → processing → done/failed)
- Generate articles at scale with consistent quality

---

## Setup: Anthropic API Key Configuration

### Step 1: Get Your Anthropic API Key

1. Go to **https://console.anthropic.com**
2. Sign in to your account (create one if needed)
3. Navigate to **API Keys** section
4. Click **Create Key** button
5. Copy the generated API key (starts with `sk-ant-`)
6. **Keep it secret!** Never commit it to version control

### Step 2: Add to `.env` File

Edit your `.env` file in the project root:

```bash
# Find this line:
ANTHROPIC_API_KEY=

# Replace with your key:
ANTHROPIC_API_KEY=sk-ant-your-actual-key-here
```

### Step 3: Verify Configuration

```bash
cd /path/to/newsmedia
php artisan config:clear
php artisan cache:clear

# Test the configuration
php artisan tinker
>>> echo config('services.anthropic.key') ? 'Configured ✅' : 'Not configured ❌';
>>> exit
```

### Step 4: In Production

For production deployment (e.g., Vercel, Railway, etc.):

1. Add `ANTHROPIC_API_KEY` as an environment variable in your hosting dashboard
2. Do **NOT** commit `.env` files to GitHub
3. The `.env.example` file can have `ANTHROPIC_API_KEY=` as a placeholder

---

## Configuration Details

The feature is configured in `config/services.php`:

```php
'anthropic' => [
    'key' => env('ANTHROPIC_API_KEY'),
    'url' => 'https://api.anthropic.com/v1/messages',
    'model' => 'claude-3-5-sonnet-20241022',  // Latest Claude model
],
```

---

## How to Use

### 1. Create a Keyword

Go to **Admin → Keywords → Create**

**Required Fields:**
- **Keyword** — The target keyword/topic (e.g., "Teknologi AI 2024")
- **Category** — Article category
- **Intent** — Purpose of the keyword:
  - `informational` — Educational/informative articles
  - `navigational` — How-to, guide articles
  - `transactional` — Product/service focused
  - `commercial` — Review/comparison articles
- **Focus Tone** — Writing style (e.g., "professional", "casual", "technical", "conversational")
- **Target Words** — Article length in words (500-5000)

**Optional Fields:**
- **Description** — Additional context/notes
- **Use Humanizer** — Apply text humanization (if available)

### 2. Generate Article

After creating a keyword:

1. Go to **Admin → Keywords**
2. Click on the keyword you created
3. Click **🤖 Generate Article** button
4. Status changes: `pending` → `processing` → `done` (or `failed`)
5. If successful, generated article appears in the keyword details page

### 3. Review & Publish

Once the article is generated:

1. Review the auto-generated content for quality
2. Make any edits if needed
3. Copy the generated article to a new blog post
4. Publish it to your site

---

## Feature Status & Troubleshooting

### Check Current Keyword Status

```bash
# In admin panel: Keywords index shows status badges
# Pending (yellow) → Processing (blue) → Done (green) or Failed (red)
```

### If Generation Fails

**Check these things:**

1. **API Key validity**
   ```bash
   php artisan tinker
   >>> config('services.anthropic.key')
   # Should output your key, not null
   ```

2. **API Quota**
   - Check your Anthropic API usage at https://console.anthropic.com/usage
   - Ensure you have credit/quota remaining

3. **Network connectivity**
   - Verify your server can reach `api.anthropic.com`
   - Check firewall/proxy settings

4. **Error message in database**
   - Go to Admin → Keywords, find failed keyword
   - Check `error_msg` field for details
   - Common: rate limits, invalid API key, network timeout

### View Generation Error Details

```bash
php artisan tinker
>>> $keyword = \App\Models\Keyword::find(XX);  # Replace XX with ID
>>> echo $keyword->error_msg;  # Shows the error message
>>> exit
```

---

## Pricing & Limits

### Anthropic API Pricing

- **Input tokens** — $3 per 1M tokens
- **Output tokens** — $15 per 1M tokens
- **Typical article** — ~2,000 tokens input + ~1,500 tokens output = ~$0.03-0.05 per article

Check latest pricing at: https://www.anthropic.com/pricing

### Rate Limits

- **Free tier:** ~300K tokens/month (after free credits)
- **Paid:** Scales with your subscription
- **Rate:** 10,000 tokens/min (per-minute limit)

---

## Advanced Configuration

### Change the Model

To use a different Claude model, edit `config/services.php`:

```php
'anthropic' => [
    'model' => 'claude-opus-4-1',  // Faster, cheaper
    // or
    'model' => 'claude-3-sonnet-20240229',  // Balanced
    // or
    'model' => 'claude-3-opus-20240229',  // Most powerful
],
```

### Custom Generation Prompts

Edit `app/Services/ArticleGeneratorService.php` to customize:
- System instructions
- Tone & style guidelines
- Output format
- Content requirements

---

## Example Workflow

```
Step 1: Create Keyword
  Name: "AI dan Kesehatan Indonesia"
  Category: "Teknologi"
  Intent: "informational"
  Tone: "professional"
  Target: 2000 words

Step 2: Generate
  Click "Generate Article" button
  Status: pending → processing → done ✅

Step 3: Review
  Article generated with:
  - Engaging title
  - SEO-optimized content
  - Proper structure (intro, body, conclusion)
  - Related links suggestions
  - FAQ section

Step 4: Publish
  Copy content → Create new article → Publish

Result: Professional article published in minutes! 🚀
```

---

## Limitations

⚠️ **Current Limitations:**
- One generation at a time (async queue not implemented yet)
- Manual review recommended before publishing
- No automatic publishing (safety feature)
- Requires manual linking to keywords

---

## FAQ

**Q: What if I don't have an API key?**
A: Without an API key, the generate button won't work. Create one free at https://console.anthropic.com (includes free trial credits).

**Q: Can I use a different AI provider?**
A: Currently only Anthropic Claude is integrated. Adding OpenAI/Gemini support would require code changes to `ArticleGeneratorService`.

**Q: How long does generation take?**
A: Typically 30-90 seconds depending on article length and API load.

**Q: Can I batch generate?**
A: Not yet. Current implementation is one-at-a-time. Batch processing would require queue implementation.

**Q: Is the generated content original?**
A: Yes, Claude generates unique content each time. However, always review for accuracy and your specific needs.

---

## Support

For issues:
1. Check error messages in the `Keywords` admin panel
2. Review API key configuration
3. Check Anthropic console for API status
4. Review logs: `storage/logs/laravel.log`

For questions about Claude AI:
- Documentation: https://docs.anthropic.com
- API Reference: https://docs.anthropic.com/en/api
- Status: https://status.anthropic.com
