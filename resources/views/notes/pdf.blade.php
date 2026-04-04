<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $note->title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #213448;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #547792;
            margin-bottom: 30px;
            padding-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #213448;
            margin: 0 0 20px 0;
            text-align: center;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            font-size: 11px;
            color: #547792;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .meta-table td {
            vertical-align: top;
        }
        .meta-tags {
            text-align: left;
            width: 60%;
        }
        .meta-date {
            text-align: right;
            width: 40%;
        }
        .hashtags-list {
            display: inline-block;
            max-width: 100%;
            font-style: italic;
        }
        .content {
            font-size: 14px;
            text-align: justify;
        }
        /* Quill output styling adjustments for PDF */
        .content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 20px auto;
        }
        .content h1, .content h2, .content h3 {
            color: #213448;
            margin-top: 25px;
        }
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            font-size: 10px;
            text-align: center;
            color: #94B4C1;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">{{ $note->title }}</h1>
        <table class="meta-table">
            <tr>
                <td class="meta-tags">
                    @if($note->hashtags)
                        Tags: <span class="hashtags-list">{{ $note->hashtags }}</span>
                    @else
                        Tags: -
                    @endif
                </td>
                <td class="meta-date">
                    Dibuat pada: {{ $note->note_date->locale('id')->isoFormat('D MMMM YYYY') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        {!! $note->content !!}
    </div>

    <div class="footer">
        Dicetak dari NoteApp — {{ now()->isoFormat('D MMMM YYYY, HH:mm') }}
    </div>
</body>
</html>
